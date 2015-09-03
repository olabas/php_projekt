<?php

/**
 * Comments model.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Model;

use Silex\Application;

/**
 * Class CommentsModel.
 *
 * @package Model
 * @use Silex\Application
 */
class CommentsModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    /**
     * Get all comments.
     *
     * @access public
     * @param integer $id Post's id
     * @return array Result
     */
    public function getAll($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = '
                SELECT 
                    comments.id, comments.content, comments.comment_date,
                    comments.post_id, users.id, users.login, posts.id
                FROM 
                    comments
                INNER JOIN 
                    users 
                ON 
                    (users.id = comments.user_id) 
                INNER JOIN
                    posts
                ON
                    (posts.id = comments.post_id)
                WHERE
                    posts.id = :id
                ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : $result;
        } else {
            return array();
        }
    }

    /**
     * Add comment.
     *
     * @access public
     * @param array $comment Comment's data
     * @return mixed Result
     */
    public function addComment($comment)
    {
        return $this->db->insert('comments', $comment);
    }
}