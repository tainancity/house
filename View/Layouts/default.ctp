<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-TW">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>空地空屋管理系統::<?php echo $title_for_layout; ?></title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css('jquery-ui');
        echo $this->Html->css('bootstrap');
        echo $this->Html->css('default');
        echo $this->Html->script('jquery');
        echo $this->Html->script('jquery-ui');
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('olc');
		echo $this->Html->script('scripts');
        echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <div class="container">
            <div id="header">
                <h1><?php
                    if ($loginMember['group_id'] == 0) {
                        echo $this->Html->link('空地空屋管理系統', '/');
                    } else {
                        echo $this->Html->link('空地空屋管理系統', '/admin/tasks');
                    }
                    ?></h1>
            </div>
            <div id="content">
                <div class="btn-group pull-right">
                    <?php
                    switch ($loginMember['group_id']) {
                        case 1://管理者
                            echo $this->Html->link('空地空屋列管資料', '/admin/tasks', array('class' => 'btn btn-default'));
							echo $this->Html->link('列管編輯記錄', '/admin/PlaceLogs', array('class' => 'btn btn-default'));
                            echo $this->Html->link('專案', '/admin/projects', array('class' => 'btn btn-default'));
                            echo $this->Html->link('帳號管理', '/admin/members', array('class' => 'btn btn-default'));
                            echo $this->Html->link('群組', '/admin/groups', array('class' => 'btn btn-default'));
                            echo $this->Html->link('登出', '/members/logout', array('class' => 'btn btn-default'));
                            break;
                        case 0://未登入
							echo $this->Html->link('公開空地空屋列管地圖', '/tasks', array('class' => 'btn btn-default'));
							echo $this->Html->link('地址座標查詢', '/doors', array('class' => 'btn btn-default'));
							echo $this->Html->link('地號座標查詢', '/lands', array('class' => 'btn btn-default'));
                            echo $this->Html->link('管理者登入', '/members/login', array('class' => 'btn btn-default'));
                            break;
                        default://局處
                            echo $this->Html->link('空地空屋列管資料', '/admin/tasks', array('class' => 'btn btn-default'));
                            echo $this->Html->link('追蹤項目', '/admin/trackers/user', array('class' => 'btn btn-default'));
                            echo $this->Html->link('登出', '/members/logout', array('class' => 'btn btn-default'));
                    }
                    if (!empty($actions_for_layout)) {
                        foreach ($actions_for_layout as $title => $url) {
                            echo $this->Html->link($title, $url, array('class' => 'btn btn-default'));
                        }
                    }
                    ?>
                </div>

                <?php echo $this->Session->flash(); ?>
                <div id="viewContent"><?php echo $content_for_layout; ?></div>
            </div>
            <div id="footer">
                &nbsp;<br /><br />
                <div class="pull-right">
                    本系統由 研考會-智慧發展中心  建置，如有疑問請聯繫 <a href="mailto:shinryuken@mail.tainan.gov.tw">shinryuken@mail.tainan.gov.tw</a>
                </div>
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