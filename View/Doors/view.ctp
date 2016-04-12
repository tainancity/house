<div id="DoorsView">
    <h3><?php echo __('View 門牌', true); ?></h3><hr />
    <div class="col-md-12">

        <div class="col-md-2">區碼</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['area_code']) {

                echo $this->data['Door']['area_code'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">區域</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['area']) {

                echo $this->data['Door']['area'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">村里</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['cunli']) {

                echo $this->data['Door']['cunli'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">鄰</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['lin']) {

                echo $this->data['Door']['lin'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">路段</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['road']) {

                echo $this->data['Door']['road'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">地名</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['place']) {

                echo $this->data['Door']['place'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">巷</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['lane']) {

                echo $this->data['Door']['lane'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">弄</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['alley']) {

                echo $this->data['Door']['alley'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">號</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['number']) {

                echo $this->data['Door']['number'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">樓層</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['floor']) {

                echo $this->data['Door']['floor'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">經度</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['longitude']) {

                echo $this->data['Door']['longitude'];
            }
?>&nbsp;
        </div>
        <div class="col-md-2">緯度</div>
        <div class="col-md-9"><?php
            if ($this->data['Door']['latitude']) {

                echo $this->data['Door']['latitude'];
            }
?>&nbsp;
        </div>
    </div>
    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('門牌 List', true), array('action' => 'index')); ?> </li>
        </ul>
    </div>
    <div id="DoorsViewPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('a.DoorsViewControl').click(function() {
                $('#DoorsViewPanel').parent().load(this.href);
                return false;
            });
        });
        //]]>
    </script>
</div>