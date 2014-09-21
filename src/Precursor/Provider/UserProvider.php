<?php

/**
 * Proveedor de Autenticación de usuarios
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Provider
 */

namespace Precursor\Provider;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model\Usuario,
    Symfony\Component\Security\Core\User\UserProviderInterface,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\User,
    Symfony\Component\Security\Core\Exception\UnsupportedUserException,
    Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProvider implements UserProviderInterface
{

    /**
     * @var Connection $conn
     */
    private $conn;

    /**
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param string $alias Alias del usuario
     * @return User
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($alias)
    {
        $usuarioModelo = new Usuario($this->conn);
        $usuario = $usuarioModelo->getUsuarioPorAlias($alias);

        if (empty($usuario)) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $alias));
        }

        return new User($usuario['alias'], $usuario['clave'], explode(',', $usuario['perfil']), true, true, true, true);
    }

    /**
     * @param UserInterface $user
     * @return User
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }

}