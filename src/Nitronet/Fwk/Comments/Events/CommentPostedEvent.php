<?php
namespace Nitronet\Fwk\Comments\Events;

use Fwk\Events\Event;
use Nitronet\Fwk\Comments\CommentFormInterface;
use Nitronet\Fwk\Comments\CommentInterface;
use Nitronet\Fwk\Comments\CommentsService;
use Nitronet\Fwk\Comments\ThreadInterface;

class CommentPostedEvent extends Event
{
    const NAME = 'commentPosted';

    public function __construct(CommentFormInterface $commentForm, CommentInterface $comment, ThreadInterface $thread,
        CommentsService $service
    ) {
        parent::__construct(self::NAME, array(
            'commentForm'   => $commentForm,
            'comment'       => $comment,
            'thread'        => $thread,
            'error'         => null,
            'service'       => $service
        ));
    }

    /**
     * @return CommentsService
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * @return CommentFormInterface
     */
    public function getCommentForm()
    {
        return $this->commentForm;
    }

    /**
     * @return CommentInterface
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return ThreadInterface
     */
    public function getThread()
    {
        return $this->thread;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}