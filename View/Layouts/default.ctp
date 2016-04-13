<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            House::
            <?php echo $title_for_layout; ?>
        </title><?php
        echo $this->Html->meta('icon');
        echo $this->Html->css('jquery-ui');
        echo $this->Html->css('bootstrap');
        echo $this->Html->css('default');
        echo $this->Html->script('jquery');
        echo $this->Html->script('jquery-ui');
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('olc');
        echo $scripts_for_layout;
        ?>
        <style>
            .ajax-loader {
                position: absolute;
                left: 40%;
                top: 50%;
                font-size: 50pt;
                background-color: pink;
                color: black;
                border: 1px solid yellow;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div id="header">
                <h1><?php echo $this->Html->link('House', '/'); ?></h1>
            </div>
            <div id="content">
                <div class="btn-group">
                    <?php if ($this->Session->read('Auth.User.id')): ?>
                        <?php echo $this->Html->link('任務', '/admin/tasks', array('class' => 'btn')); ?>
                        <?php echo $this->Html->link('Members', '/admin/members', array('class' => 'btn')); ?>
                        <?php echo $this->Html->link('Groups', '/admin/groups', array('class' => 'btn')); ?>
                        <?php echo $this->Html->link('Logout', '/members/logout', array('class' => 'btn')); ?>
                    <?php else: ?>
                        <?php echo $this->Html->link('Login', '/members/login', array('class' => 'btn')); ?>
                    <?php endif; ?>
                    <?php
                    if (!empty($actions_for_layout)) {
                        foreach ($actions_for_layout as $title => $url) {
                            echo $this->Html->link($title, $url, array('class' => 'btn'));
                        }
                    }
                    ?>
                </div>

                <?php echo $this->Session->flash(); ?>
                <div id="viewContent"><?php echo $content_for_layout; ?></div>
            </div>
            <div id="footer">
                &nbsp;
            </div>
        </div>
        <?php
        echo $this->element('sql_dump');
        ?>
        <script type="text/javascript">
            //<![CDATA[
            $(function () {
                $('a.dialogControl').click(function () {
                    dialogFull(this);
                    return false;
                });
            });
            //]]>
        </script>
    </body>
</html>