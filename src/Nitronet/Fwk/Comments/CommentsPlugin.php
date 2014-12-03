<?php
namespace Nitronet\Fwk\Comments;


use Fwk\Core\Application;
use Fwk\Core\Components\ResultType\PhpTemplateResultType;
use Fwk\Core\Components\ResultType\ResultTypeServiceLoadedEvent;
use Fwk\Core\Plugin;
use Fwk\Di\ClassDefinition;
use Fwk\Di\Container;
use Fwk\Core\Action\ProxyFactory as PF;


class CommentsPlugin implements Plugin
{
    protected $config = array();

    public function __construct(array $config = array())
    {
        $this->config = array_merge(array(
            'dbService'     => 'db',
            'sessionService'    => 'session',
            'rendererService'   => 'formRenderer',
            'threadsTable'  => 'comments_threads',
            'threadEntity'  => 'Nitronet\Fwk\Comments\Model\Thread',
            'commentsTable' => 'comments',
            'commentEntity' => 'Nitronet\Fwk\Comments\Model\Comment',
            'commentForm'   => 'Nitronet\Fwk\Comments\Forms\AnonymousCommentForm',
            'autoThread'    => false,
            'autoApprove'   => true,
            'dateFormat'    => 'Y-m-d H:i:s',
            'serviceName'   => 'comments'
        ), $config);
    }

    /**
     * Adds Plugin's services to the existing Container
     *
     * @param Container $container App's Services Container
     *
     * @return void
     */
    public function loadServices(Container $container)
    {
        // service
        $defService = new ClassDefinition('Nitronet\Fwk\Comments\CommentsService', array(
            '@'. $this->cfg('dbService', 'db'),
            array(
                'threadsTable'  => $this->cfg('threadsTable', 'comments_threads'),
                'threadEntity'  => $this->cfg('threadEntity', 'Nitronet\Fwk\Comments\Model\Thread'),
                'commentsTable' => $this->cfg('commentsTable', 'comments'),
                'commentEntity' => $this->cfg('commentEntity', 'Nitronet\Fwk\Comments\Model\Comment'),
                'autoThread'    => $this->cfg('autoThread', false),
                'autoApprove'   => $this->cfg('autoApprove', true),
                'dateFormat'    => $this->cfg('dateFormat', 'Y-m-d H:i:s'),
            )
        ));

        $container->set($this->cfg('serviceName', 'comments'), $defService, true);

        $container->setProperty('commentsServiceName', $this->cfg('serviceName', 'comments'));
        $container->setProperty('sessionServiceName', $this->cfg('sessionService', 'session'));
        $container->setProperty('sessionServiceName', $this->cfg('rendererService', 'formRenderer'));

        $container->setProperty('commentForm', $this->cfg('commentForm', 'Nitronet\Fwk\Comments\Forms\AnonymousCommentForm'));
    }

    /**
     * Adds Actions and Listeners to the Application
     *
     * @param Application $app The running Application
     *
     * @return void
     */
    public function load(Application $app)
    {
        $app->register('CommentsThread', PF::factory('Nitronet\Fwk\Comments\Controllers\Thread:show'));
        $app->register('CommentsCount', PF::factory('Nitronet\Fwk\Comments\Controllers\Thread:countComments'));
        $app->register('CommentPost', PF::factory('Nitronet\Fwk\Comments\Controllers\Comment:post'));
    }


    public function onResultTypeServiceLoaded(ResultTypeServiceLoadedEvent $event)
    {
        $rts = $event->getResultTypeService();
        $rts->addType('php.comments', new PhpTemplateResultType(array('templatesDir' => __DIR__ .'/templates')));

        $rts->register('CommentsThread', 'error', 'php.comments', array('file' => 'thread_error.php'));
        $rts->register('CommentsThread', 'success', 'php.comments', array('file' => 'thread.php'));

        $rts->register('CommentPost', 'form_error', 'redirect', array('uri' => ':back'));
        $rts->register('CommentPost', 'success', 'redirect', array('uri' => ':back'));
        $rts->register('CommentPost', 'form', 'php.comments', array('file' => 'post.php'));

    }

    protected function cfg($key, $default = false)
    {
        return (array_key_exists($key, $this->config) ? $this->config[$key] : $default);
    }
}