<?php
/**
 * Modelo de Artículos o Noticias
 *
 * @author     Ramon Serrano <ramon.calle.88@gmail.com>
 * @author     Javier Madrid <javiermadrid19@gmail.com>  
 * 
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Articulo extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'articulo');
    }
    
    /**
     * @param string $orderBy
     * @return array
     */
    public function getTodo($orderBy = 'fecha_pub')
    {
        $this->_queryBuilder
                ->select(array('a.*', 'usuario.nombre as autor'))
                ->from($this->_table, 'a')
                ->innerJoin('a', 'usuario', '', 'a.id_autor = usuario.id')
                ->orderBy($orderBy, 'DESC');
        
        $sql = $this->_queryBuilder->getSQL();
        
        return $this->_select($sql);
    }

    /**
     * @param int $id Id del artículo
     *
     * @return array
     */
    public function getArticuloYEtiquetas($id)
    {
        $fields = array('articulo.*', 'usuario.nombre as autor', 'categoria.nombre as categoria');
        $join   = array(
            array('usuario', 'id_autor', 'usuario.id', '='),
            array('categoria', 'articulo.id_categoria', 'categoria.id', '=')
            );
        $where  = "WHERE articulo.id = $id";
        $articulo = parent::getTodo($fields, $join, $where);

        if (isset($articulo[0]) && !empty($articulo[0])) {

            $articulo = $articulo[0];

            $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);
            $fields = array('etiqueta.id', 'etiqueta.nombre as etiqueta');
            $join = array('etiqueta', 'id_etiqueta', 'etiqueta.id', '=');
            $etiquetas = $etiquetasArticuloModelo->_selectFields($fields, $join, 'WHERE id_articulo = ?', array($id));

            if (!empty($etiquetas)) {
                foreach ($etiquetas as $etiqueta) {
                    $articulo['etiquetas'][$etiqueta['id']] = $etiqueta['etiqueta'];
                }
            } else {
                $articulo['etiquetas'] = array();
            }
            
        }

        return $articulo;
    }
    
    /**
     * @param array $fields Campos que se desean del registro
     * 
     * @return array Arreglo de artículos
     */
    public function getArticulos(array $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                'articulo.*',
                'categoria.nombre as categoria'
            );
        }
        $join = array('categoria', 'articulo.id_categoria', 'categoria.id', '=');
        $articulos = parent::getTodo($fields, $join);
        
        $comentarioModelo = new Comentario($this->_db);
        foreach ($articulos as $i => $articulo) {
            $comentarios = $comentarioModelo->getComentariosArticulo($articulo['id']);
            $articulos[$i]['comentarios'] = $comentarios;
        }
        return $articulos;
    }

    /**
     * @param string $titulo Titulo de la noticia
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getArticuloBy($titulo)
    {
        $sql = $this->queryBuilder()->select(array('*'))
            ->from('articulo', 'a')
            ->where($this->queryBuilder()->expr()->orX(
                $this->queryBuilder()->expr()->like('titulo', '?'),
                $this->queryBuilder()->expr()->like('descripcion', '?'),
                $this->queryBuilder()->expr()->like('contenido', '?')
                ))
            ->getSQL();
        return $this->_select($sql, $join = array(), '', array("%$titulo%", "%$titulo%", "%$titulo%"));
    }

    /**
     * @param int $idCategoria Id de la categoria del articulo
     *
     * @return array
     */
    public function getArticulosByCategoria($idCategoria)
    {
        return parent::getTodo(array('*'), array(), "WHERE id_categoria = $idCategoria");
    }

    /**
     * @param int $idEtiqueta  Id de la etiqueta del articulo
     *
     * @return array
     */
    public function getArticulosByEtiqueta($idEtiqueta)
    {
        # Set table 
        $this->setTable('articulos_etiquetas');
        $join = array('articulo', 'id_articulo', 'articulo.id', '=');
        return parent::getTodo(array('*'), $join, "WHERE id_etiqueta = $idEtiqueta");
    }

    /**
     * @param int $idAutor        Id del usuario actual logueado
     * @param int $idCategoria    Id de la categoría del artículo
     * @param string $imagen      Url de la imagen del artículo
     * @param string $titulo      Título del artículo
     * @param string $descripcion Descripción del artículo
     * @param string $contenido   Contenido HTML del artículo
     * @param array $etiquetas    Arreglo de las estiquetas seleccionadas
     *
     * @return array Filas afectadas de artículo y etiquetas
     */
    public function guardar($idAutor, $idCategoria, $imagen, $titulo, $descripcion, $contenido, array $etiquetas = array())
    {
        $data = array(
            'id_autor'     => $idAutor,
            'id_categoria' => $idCategoria,
            'imagen'       => $imagen,
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'contenido'    => $contenido,
            'fecha_pub'    => date('Y-m-d H:m:s'),
            'creado'       => date('Y-m-d H:m:s')
        );

        $this->_db->beginTransaction();
        
        $filasAfectadas = 0;
        $etiquetaAgregadas = array();

        try {
            $filasAfectadas = $this->_insert($data);

            if ($filasAfectadas == 1 && !empty($etiquetas)) {

                $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);
                
                foreach ($etiquetas as $etiqueta) {
                    $filasAfectadasEtiqueta = $etiquetasArticuloModelo->guardar($this->id, $etiqueta);

                    // Agregar la etiqueta si se inserto en la tabla, para el mensaje del usuario
                    if ($filasAfectadasEtiqueta == 1) {
                        $etiquetaAgregadas[] = $etiqueta;
                    }
                }
            }
            $this->_db->commit();
        } catch(\Exception $e) {
            $this->_db->rollBack();
            throw $e;
        }
        return array(
            'articulo'  => $filasAfectadas,
            'etiquetas' => $etiquetaAgregadas
        );
    }

    /**'estatus' => 'A',
     * @param int $id             Id del artículo
     * @param int $idAutor        Id del usuario actual logueado
     * @param int $idCategoria    Id de la categoría del artículo
     * @param string $imagen      Url de la imagen del artículo
     * @param string $titulo      Título del artículo
     * @param string $descripcion Descripción del artículo
     * @param string $contenido   Contenido HTML del artículo
     * @param array $etiquetas    Arreglo de las estiquetas seleccionadas
     *
     * @return array|int Filas afectadas
     */
    public function modificar($id, $idAutor, $idCategoria, $imagen, $titulo, $descripcion, $contenido, array $etiquetas = array())
    {
        $data = array(
            'id_autor'     => $idAutor,
            'id_categoria' => $idCategoria,
            'imagen'       => $imagen,
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'contenido'    => $contenido,
        );

        $filasAfectadas = $this->_update($data, array('id' => $id));

        if (!empty($etiquetas)) {
            $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);
            
            $etiquetasAgregadas = 0;
            
            foreach ($etiquetas as $etiqueta) {
                $etiquetasAgregadas += $etiquetasArticuloModelo->guardar($id, $etiqueta);
            }
            
            return array(
                'articulo'  => $filasAfectadas,
                'etiquetas' => $etiquetasAgregadas
            );
        } else {
            return $filasAfectadas;
        }
    }
    
    /**
     * @param int $id Id del artículo
     *
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        $filasAfectadas = 0;

        $comentarioModelo = new Comentario($this->_db);

        $filasAfectadas += $comentarioModelo->_delete(array('id_articulo' => $id));

        $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);

        $filasAfectadas += $etiquetasArticuloModelo->_delete(array('id_articulo' => $id));

        $filasAfectadas += $this->_delete(array('id' => $id));

        return $filasAfectadas;
    }

}
