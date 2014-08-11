<?php
/**
 * Description of UserProvider.php.
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Provider
 */

namespace Precursor\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;
 
class UserProvider implements UserProviderInterface
{
    private $conn;
 
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
 
    public function loadUserByUsername($username)
    {
        $stmt = $this->conn->executeQuery('SELECT `usuario`.*, `perfil`.`nombre` as perfil FROM `usuario` INNER JOIN perfil ON id_perfil = `perfil`.id WHERE alias = ?', array($username));
 
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
 
        return new User($user['alias'], $user['clave'], explode(',', $user['perfil']), true, true, true, true);
    }
 
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}