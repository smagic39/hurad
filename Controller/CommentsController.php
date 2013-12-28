<?php
/**
 * Comment Controller
 *
 * PHP 5
 *
 * @link http://hurad.org Hurad Project
 * @copyright Copyright (c) 2012-2013, Hurad (http://hurad.org)
 * @package app.Controller
 * @since Version 0.1.0
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Class CommentsController
 *
 * @property Comment $Comment
 * @property Post $Post
 * @property User $User
 */
class CommentsController extends AppController
{

    /**
     * An array containing the names of helpers this controller uses. The array elements should
     * not contain the "Helper" part of the class name.
     *
     * Example: `public $helpers = array('Html', 'Js', 'Time', 'Ajax');`
     *
     * @var mixed A single name as a string or a list of names as an array.
     */
    public $helpers = ['AdminLayout', 'Gravatar'];

    /**
     * Array containing the names of components this controller uses. Component names
     * should not contain the "Component" portion of the class name.
     *
     * Example: `public $components = array('Session', 'RequestHandler', 'Acl');`
     *
     * @var array
     */
    public $components = ['RequestHandler', 'Paginator'];

    /**
     * Paginate settings
     *
     * @var array
     */
    public $paginate = [
        'Comment' => [
            'conditions' => [
                'Comment.status' => ['approved', 'disapproved'],
            ],
            'limit' => 25,
            'order' => [
                'Comment.created' => 'desc'
            ]
        ]
    ];

    /**
     * Called before the controller action. You can use this method to configure and customize components
     * or perform logic that needs to happen before each controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'add', 'reply');
    }

    /**
     * List of comments
     */
    public function index()
    {
        $this->Comment->recursive = 0;

        $this->Paginator->settings = Hash::merge(
            $this->paginate,
            ['Conditions' => ['Comment.status' => 'disapproved']]
        );
        $this->set('comments', $this->Paginator->paginate('Comment'));
    }

    /**
     * Add comment
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Comment->create();

            if ($this->Auth->loggedIn()) {
                $user = $this->Comment->User->getUser($this->Auth->user('id'));
                $this->request->data['Comment']['user_id'] = $user['User']['id'];
                $this->request->data['Comment']['author'] = $user['UserMeta']['display_name'];
                $this->request->data['Comment']['author_email'] = $user['User']['email'];
                $this->request->data['Comment']['author_url'] = $user['User']['url'];
            }

            if ($this->Auth->user('role') == 'administrator') {
                $this->request->data['Comment']['author_url'];
            }

            $request = new CakeRequest();
            $this->request->data['Comment']['author_ip'] = $request->clientIp();
            $this->request->data['Comment']['agent'] = env('HTTP_USER_AGENT');

            $this->request->data['Comment']['author_url'] = HuradSanitize::url(
                $this->request->data['Comment']['author_url']
            );
            if ($this->Comment->save($this->request->data)) {
                $this->redirect($this->referer());
                $this->Hurad->sendEmail(
                    $this->request->data['Comment']['author_email'],
                    __d('hurad', 'Comment Submit'),
                    'add_comment',
                    __d('hurad', 'Your comment submit in blog waiting to approve by admin.')
                );
                $this->Session->setFlash(
                    __d('hurad', 'The comment has been saved'),
                    'flash_message',
                    array('class' => 'success')
                );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                    __d('hurad', 'The comment could not be saved. Please, try again.'),
                    'flash_message',
                    array('class' => 'danger')
                );
            }
        }
    }

    /**
     * List of comments
     *
     * @param null|string $action
     */
    public function admin_index($action = null)
    {
        $this->set('title_for_layout', __d('hurad', 'Comments'));
        $this->Comment->recursive = 0;

        if (isset($this->request->params['named']['q'])) {
            App::uses('Sanitize', 'Utility');
            $q = Sanitize::clean($this->request->params['named']['q']);
            $this->Paginator->settings = array_merge(
                $this->paginate,
                array(
                    'Comment' => array(
                        'conditions' => array(
                            'Comment.content LIKE' => '%' . $q . '%',
                        )
                    )
                )
            );
        }

        switch ($action) {
            case 'moderated':
                $this->set('title_for_layout', __d('hurad', 'Moderated comments'));
                $this->Paginator->settings = array_merge(
                    $this->paginate,
                    array(
                        'Comment' => array(
                            'conditions' => array(
                                'Comment.status' => 'disapproved',
                            )
                        )
                    )
                );
                break;

            case 'approved':
                $this->set('title_for_layout', __d('hurad', 'Approved comments'));
                $this->Paginator->settings = array_merge(
                    $this->paginate,
                    array(
                        'Comment' => array(
                            'conditions' => array(
                                'Comment.status' => 'approved',
                            )
                        )
                    )
                );
                break;

            case 'spam':
                $this->set('title_for_layout', __d('hurad', 'Spams'));
                $this->Paginator->settings = array_merge(
                    $this->paginate,
                    array(
                        'Comment' => array(
                            'conditions' => array(
                                'Comment.status' => 'spam',
                            )
                        )
                    )
                );
                break;

            case 'trash':
                $this->set('title_for_layout', __d('hurad', 'Trashes'));
                $this->Paginator->settings = array_merge(
                    $this->paginate,
                    array(
                        'Comment' => array(
                            'conditions' => array(
                                'Comment.status' => 'trash',
                            )
                        )
                    )
                );
                break;
        }

        $countComments['all'] = $this->Comment->countComments();
        $countComments['moderated'] = $this->Comment->countComments('moderated');
        $countComments['approved'] = $this->Comment->countComments('approved');
        $countComments['spam'] = $this->Comment->countComments('spam');
        $countComments['trash'] = $this->Comment->countComments('trash');

        $this->set('countComments', $countComments);
        $this->set('comments', $this->Paginator->paginate('Comment'));
    }

    /**
     * Edit comment
     *
     * @param null|int $id
     *
     * @throws NotFoundException
     */
    public function admin_edit($id = null)
    {
        $this->set('title_for_layout', __d('hurad', 'Edit Comment'));
        $this->Comment->id = $id;
        if (!$this->Comment->exists()) {
            throw new NotFoundException(__d('hurad', 'Invalid comment'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Comment->save($this->request->data)) {
                $this->Session->setFlash(
                    __d('hurad', 'The comment has been saved'),
                    'flash_message',
                    array('class' => 'success')
                );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                    __d('hurad', 'The comment could not be saved. Please, try again.'),
                    'flash_message',
                    array('class' => 'danger')
                );
            }
        } else {
            $this->request->data = $comment = $this->Comment->read(null, $id);
        }
        $user = $this->Comment->User->find('first');
        $this->set(compact('comment', 'user'));
    }

    /**
     * Delete comment
     *
     * @param null|int $id
     *
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     */
    public function admin_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Comment->id = $id;
        if (!$this->Comment->exists()) {
            throw new NotFoundException(__d('hurad', 'Invalid comment'));
        }
        if ($this->Comment->delete()) {
            $this->Session->setFlash(__d('hurad', 'Comment was deleted'), 'flash_message', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__d('hurad', 'Comment was not deleted'), 'flash_message', array('class' => 'danger'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Reply comment
     *
     * @param int $parentId Comment parent id
     *
     * @throws NotFoundException
     *
     */
    public function reply($parentId)
    {
        if ($this->request->is('post')) {
            $this->Comment->id = $parentId;
            if (!$this->Comment->exists()) {
                throw new NotFoundException(__d('hurad', 'Invalid comment'));
            }

            $comment = $this->Comment->getComment();

            $this->request->data['Comment']['parent_id'] = $parentId;
            $this->request->data['Comment']['post_id'] = $comment['Comment']['post_id'];

            if ($this->Auth->loggedIn()) {
                $user = $this->Comment->User->getUser($this->Auth->user('id'));

                $this->request->data['Comment']['user_id'] = $user['User']['id'];
                $this->request->data['Comment']['author'] = $user['UserMeta']['display_name'];
                $this->request->data['Comment']['author_email'] = $user['User']['email'];
                $this->request->data['Comment']['author_url'] = $user['User']['url'];
            }

            $this->Comment->create();
            if ($this->Comment->save($this->request->data)) {
                $this->Session->setFlash(
                    __d('hurad', 'The reply of comment has been saved.'),
                    'flash_message',
                    array('class' => 'success')
                );
                $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            } else {
                $this->Session->setFlash(
                    __d('hurad', 'The comment could not be saved. Please, try again.'),
                    'flash_message',
                    array('class' => 'danger')
                );
                $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            }
        }
    }

    /**
     * Comment actions
     *
     * @param null $action
     * @param null $id
     *
     * @throws NotFoundException
     * @return bool
     */
    public function admin_action($action = null, $id = null)
    {
        $this->autoRender = false;
        $this->Comment->id = $id;
        if (is_null($action)) {
            return false;
        } elseif (!$this->Comment->exists()) {
            throw new NotFoundException(__d('hurad', 'Invalid comment'));
        }
        switch ($action) {
            case 'approved':
                $data = ['id' => $id, 'status' => 'approved'];
                $msg = __d('hurad', 'Comments are approved.');
                $err = __d('hurad', 'Comments are not approved, please try again.');
                break;
            case 'disapproved':
                $data = array('id' => $id, 'status' => 'disapproved');
                $msg = __d('hurad', 'Comment are unapproved.');
                $err = __d('hurad', 'Comment are not unapproved, please try again.');
                break;
            case 'spam':
                $data = array('id' => $id, 'status' => 'spam');
                $msg = __d('hurad', 'Comment marked as spam.');
                $err = __d('hurad', 'Comment didn\'t mark as spam, please try again.');
                break;
            case 'trash':
                $data = array('id' => $id, 'status' => 'trash');
                $msg = __d('hurad', 'Comment moved to trash.');
                $err = __d('hurad', 'Comment didn\'t move to trash, please try again.');
                break;
            default:
                $this->Session->setFlash(__d('hurad', 'An error occurred.'), 'error');
                break;
        }

        if ($this->request->is('ajax')) {
            $this->Comment->save($data);
        } else {
            if ($this->Comment->save($data)) {
                $this->Session->setFlash($msg, 'flash_message', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash($err, 'flash_message', array('class' => 'danger'));
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}
