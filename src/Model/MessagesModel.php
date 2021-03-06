<?php

/**
 * Messages model.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

/**
 * Class AlbumsModel.
 *
 * @package Model
 * @use Silex\Application
 */
class MessagesModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;
    
    /**
     * Security object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $security
     */
    protected $security;


    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
        $this->security = $app['security'];
        $this->app = $app;
    }

    /**
     * Gets online username.
     *
     * @access public
     * @return array $user User's data
     */
    public function getOnlineUsername()
    {
        $token = $this->security->getToken();
        if (null !== $token) {
            $user = $token->getUsername();
        }
        return isset($user) ? $user : array();
    }

    /**
     * Gets messages.
     *
     * @access public
     * @return array Result
     */
    public function getMessages()
    {
        $login = $this -> getOnlineUsername();
        $query = '
            SELECT 
                messages.id, messages.recipient_id, recipient.login as rl,
                messages.title, messages.content, messages.date, 
                messages.is_read, sender.login as sl
            FROM 
                messages 
            INNER JOIN 
                users as recipient 
            ON 
                (recipient.id = messages.recipient_id) 
            INNER JOIN
                users as sender
            ON
                (sender.id = messages.sender_id) 
            WHERE 
                recipient.login = :login
            ORDER BY
                messages.date
            DESC
            ';
        $statement = $this->app['db']->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll();
        return !$result ? array() : $result;
    }

    /**
     * Gets messages I send.
     *
     * @access public
     * @return array Result
     */
    public function getMessagesIsend()
    {
        $login = $this -> getOnlineUsername();
        $query = '
            SELECT 
                messages.id, messages.sender_id, sender.login as sl, 
                messages.title, messages.content, messages.date,
                recipient.login as rl
            FROM 
                messages 
            INNER JOIN 
                users as sender
            ON 
                (sender.id = messages.sender_id)
            INNER JOIN
                users as recipient
            ON
                (recipient.id = messages.recipient_id) 
            WHERE 
                sender.login = :login
            ORDER BY
                messages.date
            DESC
            ';
        $statement = $this->app['db']->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll();
        return !$result ? array() : $result;

    }
    /**
     * Send message
     *
     * @access public
     * @param array $message Message's data
     * @return mixed Result
     */
    public function sendMessage($message)
    {
        return $this->db->insert('messages', $message);
    }

    /**
     * delete message
     *
     * @access public
     * @param integer $id Message's id
     * @return mixed Result
     */
    public function deleteMessage($id)
    {
        return $this->db->delete('messages', array('id' => $id));
    }
    /**
     * Gets message.
     *
     * @access public
     * @param integer $id Message's id
     * @return array Result
     */
    public function getMessage($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = '
                SELECT 
                    messages.id, messages.title, messages.content, 
                    messages.date, messages.recipient_id,
                    messages.sender_id, messages.is_read, users.login, 
                    users.name, users.surname, users.address,
                    users.email, users.phone_number 
                FROM
                    messages
                INNER JOIN
                    users
                ON
                    users.id = messages.sender_id
                WHERE
                    messages.id = :id
                ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } else {
            return array();
        }
    }

 /**
     * Gets message I send.
     *
     * @access public
     * @param integer $id Message's id
     * @return array Result
     */
    public function getMessageIsend($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = '
                SELECT 
                    messages.id, messages.title, messages.content, 
                    messages.date, messages.recipient_id,
                    messages.sender_id, messages.is_read, users.login, 
                    users.name, users.surname, users.address,
                    users.email, users.phone_number 
                FROM
                    messages
                INNER JOIN
                    users
                ON
                    users.id = messages.recipient_id
                WHERE
                    messages.id = :id
                ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } else {
            return array();
        }
    }

    /**
     * Change message status.
     *
     * @access public
     * @param array $message Message's data
     * @return mixed Result
     */
    public function changeMessageStatus($message)
    {
        if (($message['id'] != '') && ctype_digit((string)$message['id'])) {
            $data['is_read'] = 1;
            return $this->db->update('messages', $data, array('id' => $message['id']));
        } else {
            return array();
        }
    }
}
