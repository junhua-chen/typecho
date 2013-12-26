<?php
/**
 * 基本设置
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 * @version $Id$
 */

/**
 * 基本设置组件
 *
 * @author qining
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Widget_Options_General extends Widget_Abstract_Options implements Widget_Interface_Do
{
    /**
     * 输出表单结构
     *
     * @access public
     * @return Typecho_Widget_Helper_Form
     */
    public function form()
    {
        /** 构建表格 */
        $form = new Typecho_Widget_Helper_Form(Typecho_Common::url('/action/options-general', $this->options->index),
        Typecho_Widget_Helper_Form::POST_METHOD);

        /** 站点名称 */
        $title = new Typecho_Widget_Helper_Form_Element_Text('title', NULL, $this->options->title, _t('站点名称'), _t('站点的名称将显示在网页的标题处.'));
        $title->input->setAttribute('class', 'w-100');
        $form->addInput($title->addRule('required', _t('请填写站点名称')));

        /** 站点地址 */
        $siteUrl = new Typecho_Widget_Helper_Form_Element_Text('siteUrl', NULL, $this->options->originalSiteUrl, _t('站点地址'), _t('站点地址主要用于生成内容的永久链接.') 
            . ($this->options->originalSiteUrl == $this->options->rootUrl ? 
                '' : '</p><p class="message notice mono">' . _t('当前地址 <strong>%s</strong> 与上述设定值不一致',
                    $this->options->rootUrl)));
        $siteUrl->input->setAttribute('class', 'w-100 mono');
        $form->addInput($siteUrl->addRule('required', _t('请填写站点地址'))
            ->addRule('url', _t('请填写一个合法的URL地址')));

        /** 站点描述 */
        $description = new Typecho_Widget_Helper_Form_Element_Text('description', NULL, $this->options->description, _t('站点描述'), _t('站点描述将显示在网页代码的头部.'));
        $form->addInput($description);

        /** 关键词 */
        $keywords = new Typecho_Widget_Helper_Form_Element_Text('keywords', NULL, $this->options->keywords, _t('关键词'), _t('请以半角逗号 "," 分割多个关键字.'));
        $form->addInput($keywords);

        /** 注册 */
        $allowRegister = new Typecho_Widget_Helper_Form_Element_Radio('allowRegister', array('0' => _t('不允许'), '1' => _t('允许')), $this->options->allowRegister, _t('是否允许注册'),
        _t('允许访问者注册到你的网站, 默认的注册用户不享有任何写入权限.'));
        $form->addInput($allowRegister);

        /** 时区 */
        $timezoneList = array(
            "0"         => _t('格林威治(子午线)标准时间 (GMT)'),
            "3600"      => _t('中欧标准时间 阿姆斯特丹,荷兰,法国 (GMT +1)'),
            "7200"      => _t('东欧标准时间 布加勒斯特,塞浦路斯,希腊 (GMT +2)'),
            "10800"     => _t('莫斯科时间 伊拉克,埃塞俄比亚,马达加斯加 (GMT +3)'),
            "14400"     => _t('第比利斯时间 阿曼,毛里塔尼亚,留尼汪岛 (GMT +4)'),
            "18000"     => _t('新德里时间 巴基斯坦,马尔代夫 (GMT +5)'),
            "21600"     => _t('科伦坡时间 孟加拉 (GMT +6)'),
            "25200"     => _t('曼谷雅加达 柬埔寨,苏门答腊,老挝 (GMT +7)'),
            "28800"     => _t('北京时间 香港,新加坡,越南 (GMT +8)'),
            "32400"     => _t('东京平壤时间 西伊里安,摩鹿加群岛 (GMT +9)'),
            "36000"     => _t('悉尼关岛时间 塔斯马尼亚岛,新几内亚 (GMT +10)'),
            "39600"     => _t('所罗门群岛 库页岛 (GMT +11)'),
            "43200"     => _t('惠灵顿时间 新西兰,斐济群岛 (GMT +12)'),
            "-3600"     => _t('佛德尔群岛 亚速尔群岛,葡属几内亚 (GMT -1)'),
            "-7200"     => _t('大西洋中部时间 格陵兰 (GMT -2)'),
            "-10800"    => _t('布宜诺斯艾利斯 乌拉圭,法属圭亚那 (GMT -3)'),
            "-14400"    => _t('智利巴西 委内瑞拉,玻利维亚 (GMT -4)'),
            "-18000"    => _t('纽约渥太华 古巴,哥伦比亚,牙买加 (GMT -5)'),
            "-21600"    => _t('墨西哥城时间 洪都拉斯,危地马拉,哥斯达黎加 (GMT -6)'),
            "-25200"    => _t('美国丹佛时间 (GMT -7)'),
            "-28800"    => _t('美国旧金山时间 (GMT -8)'),
            "-32400"    => _t('阿拉斯加时间 (GMT -9)'),
            "-36000"    => _t('夏威夷群岛 (GMT -10)'),
            "-39600"    => _t('东萨摩亚群岛 (GMT -11)'),
            "-43200"    => _t('艾尼威托克岛 (GMT -12)')
        );

        $timezone = new Typecho_Widget_Helper_Form_Element_Select('timezone', $timezoneList, $this->options->timezone, _t('时区'));
        $form->addInput($timezone);

        /** 扩展名 */
        $attachmentTypesOptionsResult = (NULL != trim($this->options->attachmentTypes)) ? 
        array_map('trim', explode(',', $this->options->attachmentTypes)) : array();
        $attachmentTypesOptionsValue = array();
        
        if (in_array('@image@', $attachmentTypesOptionsResult)) {
            $attachmentTypesOptionsValue[] = '@image@';
        }
        
        if (in_array('@media@', $attachmentTypesOptionsResult)) {
            $attachmentTypesOptionsValue[] = '@media@';
        }
        
        if (in_array('@doc@', $attachmentTypesOptionsResult)) {
            $attachmentTypesOptionsValue[] = '@doc@';
        }
        
        $attachmentTypesOther = array_diff($attachmentTypesOptionsResult, $attachmentTypesOptionsValue);
        $attachmentTypesOtherValue = '';
        if (!empty($attachmentTypesOther)) {
            $attachmentTypesOptionsValue[] = '@other@';
            $attachmentTypesOtherValue = implode(',', $attachmentTypesOther);
        }
        
        $attachmentTypesOptions = array(
            '@image@'    =>  _t('图片文件') . ' <code>(gif jpg jpeg png tiff bmp)</code>',
            '@media@'    =>  _t('多媒体文件') . ' <code>(mp3 wmv wma rmvb rm avi flv)</code>',
            '@doc@'      =>  _t('常用档案文件') . ' <code>(txt doc docx xls xlsx ppt pptx zip rar pdf)</code>',
            '@other@'    =>  _t('其他格式 %s', ' <input type="text" class="w-50 text-s mono" name="attachmentTypesOther" value="' . htmlspecialchars($attachmentTypesOtherValue) . '" />'),
        );
        
        $attachmentTypes = new Typecho_Widget_Helper_Form_Element_Checkbox('attachmentTypes', $attachmentTypesOptions,
        $attachmentTypesOptionsValue, _t('允许上传的文件类型'), _t('用逗号 "," 将后缀名隔开, 例如: %s', '<code>cpp, h, mak</code>'));
        $form->addInput($attachmentTypes->multiMode());

        /** 提交按钮 */
        $submit = new Typecho_Widget_Helper_Form_Element_Submit('submit', NULL, _t('保存设置'));
        $submit->input->setAttribute('class', 'primary');
        $form->addItem($submit);

        return $form;
    }

    /**
     * 执行更新动作
     *
     * @access public
     * @return void
     */
    public function updateGeneralSettings()
    {
        /** 验证格式 */
        if ($this->form()->validate()) {
            $this->response->goBack();
        }

        $settings = $this->request->from('title', 'siteUrl', 'description', 'keywords', 'allowRegister', 'timezone', 'attachmentTypes');
        $settings['siteUrl'] = rtrim($settings['siteUrl'], '/');

        $attachmentTypes = array();
        if ($this->isEnableByCheckbox($settings['attachmentTypes'], '@image@')) {
            $attachmentTypes[] = '@image@';
        }
        
        if ($this->isEnableByCheckbox($settings['attachmentTypes'], '@media@')) {
            $attachmentTypes[] = '@media@';
        }
        
        if ($this->isEnableByCheckbox($settings['attachmentTypes'], '@doc@')) {
            $attachmentTypes[] = '@doc@';
        }
        
        $attachmentTypesOther = $this->request->filter('trim')->attachmentTypesOther;
        if ($this->isEnableByCheckbox($settings['attachmentTypes'], '@other@') && !empty($attachmentTypesOther)) {
            $attachmentTypes[] = implode(',', array_map('trim', explode(',', $attachmentTypesOther)));
        }
        
        $settings['attachmentTypes'] = implode(',', $attachmentTypes);
        foreach ($settings as $name => $value) {
            $this->update(array('value' => $value), $this->db->sql()->where('name = ?', $name));
        }

        $this->widget('Widget_Notice')->set(_t("设置已经保存"), 'success');
        $this->response->goBack();
    }

    /**
     * 绑定动作
     *
     * @access public
     * @return void
     */
    public function action()
    {
        $this->user->pass('administrator');
        $this->on($this->request->isPost())->updateGeneralSettings();
        $this->response->redirect($this->options->adminUrl);
    }
}
