# fwk-comments

Adds Comments management to [Fwk\Core](https://github.com/fwk/Core) Applications.

## Installation

### 1: Install the sources

Via [Composer](http://getcomposer.org):

```
{
    "require": {
        "nitronet/fwk-comments": "dev-master",
    }
}
```

If you don't use Composer, you can still [download](https://github.com/nitronet/fwk-comments/zipball/master) this repository and add it
to your ```include_path``` [PSR-0 compatible](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

### 2: Configure Plugin

First, add some INI configuration to your config.ini file

``` ini
[services]
comments.service = comments
comments.services.database = db
comments.services.session = session
comments.services.renderer = formRenderer
comments.tables.threads = comments_threads
comments.tables.comments = comments
comments.entities.thread = Nitronet\Fwk\Comments\Model\Thread
comments.entities.comment = Nitronet\Fwk\Comments\Model\Comment
comments.auto.approve = true
comments.auto.thread = true
comments.date.format = Y-m-d H:i:s
comments.form = Nitronet\Fwk\Comments\Forms\AnonymousCommentForm
```

index.php:
``` php
$app->plugin(new CommentsPlugin(array(
    'db'            => $services->getProperty('comments.services.database', 'db'),
    'sessionService'    => $services->getProperty('comments.services.session', 'session'),
    'rendererService'   => $services->getProperty('comments.services.renderer', 'formRenderer'),
    'threadsTable'  => $services->getProperty('comments.tables.threads', 'comments_threads'),
    'threadEntity'  => $services->getProperty('comments.entities.thread', 'Nitronet\Fwk\Comments\Model\Thread'),
    'commentsTable' => $services->getProperty('comments.tables.comments', 'comments'),
    'commentEntity' => $services->getProperty('comments.entities.comment', 'Nitronet\Fwk\Comments\Model\Comment'),
    'commentForm'   => $services->getProperty('comments.form', 'Nitronet\Fwk\Comments\Forms\AnonymousCommentForm'),
    'autoThread'    => $services->getProperty('comments.auto.thread', false),
    'autoApprove'   => $services->getProperty('comments.auto.approve', true),
    'dateFormat'    => $services->getProperty('comments.date.format', 'Y-m-d H:i:s'),
    'serviceName'   => $services->getProperty('comments.service', 'comments')
)));
``` 

### 4: That's it!

You can now use the embed viewHelper in your templates, like so:

#### Displaying thread
``` php
<?= $this->_helper->embed('CommentsThread', array('id' => 'blog:'. $article->getId(), type: 'threaded')); ?>
```

#### Displaying Comments count
``` php
<?= $this->_helper->embed('CommentsCount', array('id' => 'blog:'. $article->getId())); ?>
```

## Contributions / Community

- Issues on Github: https://github.com/nitronet/fwk-comments/issues
- Follow *Fwk* on Twitter: [@phpfwk](https://twitter.com/phpfwk)
