<?php
/**
 ***********************************************************************************************
 *                             COMPSOUL BLOG MODULE - LEGAL NOTICE                             *
 ***********************************************************************************************
 * The Compsoul Blog module ("the Module") is the intellectual property of Daniel Dziedzic,
 * trading under the name "Compsoul". By downloading, installing, or using the Module, you
 * ("the User") agree to be bound by the following terms and conditions:
 *
 * 1. Modification and Customization:
 * The User is permitted to modify and customize the Module for their own use, provided that
 * any substantial modifications or alterations are made in consultation with the original
 * creator, Daniel Dziedzic. The User must seek approval for any significant changes to the Module.
 *
 * 2. Non-Profit Use:
 * The Module can be used by non-profit organizations without any restrictions, subject to
 * compliance with all other terms and conditions specified herein.
 *
 * 3. Prohibited Resale:
 * The User is strictly prohibited from selling, leasing, licensing, or distributing the Module
 * in any form, either as it is or in modified versions, for commercial or monetary gain. The Module
 * is intended for non-commercial use only.
 *
 * 4. Consultation with the Creator:
 * Any attempts to modify, alter, or distribute the Module, in part or in whole, must be communicated
 * and approved by the original creator, Daniel Dziedzic. For any inquiries or consultation, please
 * contact the creator via email at daniel@compsoul.pl.
 *
 * 5. Creator Information:
 * The Module has been created by Daniel Dziedzic, trading under the name "Compsoul". For more
 * information about the creator and their work, please visit the official website at compsoul.pl
 * (for Polish) or compsoul.dev (for English).
 *
 * 6. Display of Creator Information:
 * The User agrees to include a visible and accessible link to the creator's website, compsoul.pl or
 * compsoul.dev, at the bottom of the list of posts on the frontend of their website using the Module.
 * The link should be displayed prominently and must be maintained at all times.
 *
 * Contact:
 * For technical support or to obtain permission for Module modifications, please contact the
 * creator:
 *
 * @author Daniel Dziedzic <daniel@compsoul.pl>
 * @copyright Compsoul
 * @license Free License with Attribution
 * @link https://compsoul.pl
 * @link https://compsoul.dev
 *
 * The User acknowledges and agrees that any use of the Module is subject to the terms and conditions
 * outlined in this legal notice. Failure to comply with these terms may result in legal action.
 ***********************************************************************************************
 *                                     END OF LEGAL NOTICE                                     *
 ***********************************************************************************************
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\ImagePreviewType;

class CompsoulBlog extends Module
{
    private $field_definitions;

    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'compsoulblog';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'compsoul.dev';
        $this->need_instance = 1;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Compsoul Blog');
        $this->description = $this->l('Compsoul Blog is a feature-rich module that allows you to create and manage a fully functional blog on your PrestaShop store. Enhance your website by publishing engaging blog posts, sharing valuable content, and interacting with your customers through comments and discussions. Take advantage of the SEO-friendly features, multilingual support, and easy customization options to create a compelling blogging experience for your visitors.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the Compsoul Blog module? All associated blog data, including posts and categories, will be permanently deleted. This action cannot be undone. If you plan to reinstall the module later, consider backing up your blog data first.');

        $this->ps_versions_compliancy = array('min' => '1.7.7', 'max' => _PS_VERSION_);

        $this->field_definitions = $this->getFieldDefinitions();
    }

    public function install()
    {
        $this->_clearCache('*');

        $this->installSQL();

        Configuration::updateValue('COMPSOULBLOG_FEATURED_NUMBER', 5);

        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayCompsoulBlogFeatured') &&
            $this->registerHook('displayCompsoulBlogPages') &&
            $this->registerHook('displayCompsoulBlogPage') &&
            $this->registerHook('actionAfterCreateCmsPageFormHandler') &&
            $this->registerHook('actionAfterUpdateCmsPageFormHandler') &&
            $this->registerHook('actionCMSPageFormBuilderModifier');
    }

    public function installSQL()
    {
      $fields = $this->field_definitions;
      $res = '';

      foreach ($fields as $key => $value) {
          if (isset($fields[$key]['database'])) {
              $res .= '`' . $key . '` ' . $fields[$key]['database'] . ',';
          }
      }

      $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'compsoulblog` (
          `id_compsoulblog` int(11) NOT NULL AUTO_INCREMENT,
          `id_lang` int(10) unsigned NOT NULL,
          `id_page` int(10) unsigned NOT NULL,
          ' . $res . '
          `date_add` datetime NOT NULL,
          `date_upd` timestamp NOT NULL,
          PRIMARY KEY  (`id_compsoulblog`)
      ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

      return Db::getInstance()->execute($query);
    }

    public function uninstall()
    {
        $this->_clearCache('*');
        $this->uninstallSQL();

        return parent::uninstall();
    }

    public function uninstallSQL()
    {
      $query = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'compsoulblog`';

      return Db::getInstance()->execute($query);
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerStylesheet('compsoulblog', 'modules/' . $this->name . '/views/css/compsoulblog.css', ['media' => 'all', 'priority' => 150]);
        $this->context->controller->registerJavascript('compsoulblog-compsoul', 'modules/' . $this->name . '/views/js/compsoulblog-compsoul.js', ['priority' => 150, 'position' => 'bottom',]);
        $this->context->controller->registerJavascript('compsoulblog-carousel', 'modules/' . $this->name . '/views/js/compsoulblog-carousel.js', ['priority' => 150, 'position' => 'bottom',]);
        $this->context->controller->registerJavascript('compsoulblog-blog', 'modules/' . $this->name . '/views/js/compsoulblog-blog.js',     ['priority' => 150, 'position' => 'bottom',]);
    }

    public function hookDisplayCompsoulBlogPage($params)
    {
        $cms = $params['cms'];
        $this->context->smarty->assign([
            'blog_cms' => $cms,
            'blog_pages' => $this->getBlogPages($this->context->language->id, $cms['id']),
            'img_path' => $this->getFrontImgPath(),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/blog-page.tpl');
    }

    public function hookDisplayCompsoulBlogFeatured($params)
    {
        $categories = $this->getCmsCategories($this->context->language->id);
        $number_of_featured = (int) Tools::getValue('COMPSOULBLOG_FEATURED_NUMBER', (int) Configuration::get('COMPSOULBLOG_FEATURED_NUMBER'));
        $number_of_featured = ($number_of_featured && $number_of_featured > 0) ? $number_of_featured : 5;

        $this->context->smarty->assign([
            'blog_pages' => $this->getBlogFeatured($number_of_featured, 'date_upd DESC'),
            'categories' => $categories,
            'img_path' => $this->getFrontImgPath(),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/blog-featured.tpl');
    }

    public function hookDisplayCompsoulBlogPages($params)
    {
        $category = $params['category'];
        $categories = $this->getCmsCategories($this->context->language->id);
        $id_cms_category = $category['id'];
        $id_categories = array_merge([$id_cms_category], $this->getCmsSubcategories($id_cms_category));

        $pages = $this->getBlogPages($this->context->language->id, null, $id_categories);

        $this->context->smarty->assign([
            'blog_pages' => $pages,
            'cms_category' => $category,
            'categories' => $categories,
            'img_path' => $this->getFrontImgPath(),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/blog-pages.tpl');
    }

    protected function getCmsSubcategories($id_cms_category = null)
    {
        $id_subcategories = array();
        if($id_cms_category) {
            $subcategories = CMSCategory::getChildren($id_cms_category, $this->context->language->id);

            foreach ($subcategories as $category) {
                $id_subcategory = $category['id_cms_category'];
                array_push($id_subcategories, $id_subcategory);

                $subcategory = CMSCategory::getChildren($id_subcategory, $this->context->language->id);
                if(!empty($subcategory)) {
                    $id_subcategories = array_merge($id_subcategories, $this->getCmsSubcategories($id_subcategory));
                }
            }
        }

        return $id_subcategories;
    }

    public function hookActionAfterCreateCmsPageFormHandler($params)
    {
        $this->saveCMSPageForm($params);
    }

    public function hookActionAfterUpdateCmsPageFormHandler($params)
    {
        $this->saveCMSPageForm($params);
    }

    protected function saveCMSPageForm($params)
    {
        $id_page = (int) $params['id'];
        $languages = Language::getLanguages(false);
        $fields = $params['form_data'];
        $field_definitions = $this->field_definitions;

        foreach ($languages as $lang) {
            $id_lang = (int) $lang['id_lang'];

            $update = [];
            $update['id_lang'] = (int) $id_lang;
            $update['id_page'] = (int) $id_page;

            foreach ($fields as $key => $value) {
                $isDatabaseElement = isset($field_definitions[$key]['database']) && $field_definitions[$key]['database'];
                $isTranslatable = isset($field_definitions[$key]['translatable']) && $field_definitions[$key]['translatable'];
                $isSetUpdate = isset($field_definitions[$key]['issetupdate']) && $field_definitions[$key]['issetupdate'];
                $remove = (isset($field_definitions[$key]['remove'])) ? $field_definitions[$key]['remove'] : null;

                if (($isDatabaseElement && !$isSetUpdate) || ($isDatabaseElement && $isSetUpdate && (($isTranslatable) ? $value[$id_lang] : $value)) ) {
                    $update[$key] = ($isTranslatable) ? $value[$id_lang] : $value;
                }

                if ($remove && $value && isset($field_definitions[$remove])) {
                    $update[$remove] = null;
                }
            }

            $this->upsertBlogEntry($update, $id_lang, $id_page);
        }

        $this->updateBlogImages($id_page);
    }

    public function hookActionCMSPageFormBuilderModifier($params)
    {
        $languages = Language::getLanguages(false);
        $formBuilder = $params['form_builder'];
        $fields = $this->field_definitions;
        $data = $params['data'];
        $id = (int) $params['id'];

        foreach ($fields as $key => $value) {
            $options = (isset($fields[$key]['options'])) ? $fields[$key]['options'] : [];
            $preview = (isset($fields[$key]['preview'])) ? $fields[$key]['preview'] : null;
            $type = (isset($fields[$key]['type'])) ? $fields[$key]['type'] : null;
            $isImage = (isset($fields[$key]['image'])) ? $fields[$key]['image'] : null;
            $isDatabaseElement = isset($fields[$key]['database']) && $fields[$key]['database'];
            $isTranslatable = isset($fields[$key]['translatable']) && $fields[$key]['translatable'];

            $formBuilder->add($key, $type, $options);

            foreach ($languages as $lang) {
                $id_lang = (int) $lang['id_lang'];
                $entry = $this->getBlogEntry($id_lang, $id);

                if ($isTranslatable && $isDatabaseElement && !empty($entry)) {
                    $data[$key][$id_lang] = $entry[$key];
                } elseif (!$isTranslatable && $isDatabaseElement && !$isImage && !empty($entry)) {
                    $data[$key] = $entry[$key];
                } elseif ($preview && isset($entry[$preview])) {
                    $data[$key] = $this->context->link->protocol_content . Tools::getMediaServer($entry[$preview]) . $this->_path . 'views/img/' . $entry[$preview];
                }
            }
        }

        $formBuilder->setData($data);
    }

    public function getContent()
    {
        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->afterSubmit().$this->renderForm();
    }

    protected function afterSubmit()
    {
        if (((bool)Tools::isSubmit('submitCompsoulblogModule')) == true) {
            return $this->postProcess();
        }
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCompsoulblogModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'message',
                        'name' => 'COMPSOULBLOG_ROOT',
                        'desc' => '<div class="alert alert-info">'.$this->l('Edit main category.').'</div>',
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Name'),
                        'name' => 'COMPSOULBLOG_CATEGORY_NAME',
                        'desc' => $this->l('Enter the name for the main category.'),
                        'maxlength' => 128,
                    ),
                    array(
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->l('Description'),
                        'name' => 'COMPSOULBLOG_CATEGORY_DESCRIPTION',
                        'desc' => $this->l('Enter the description for the main category.'),
                        'cols' => 8,
                        'rows' => 4,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Link Rewrite'),
                        'name' => 'COMPSOULBLOG_CATEGORY_LINK_REWRITE',
                        'desc' => $this->l('Enter the rewrite link for the main category. Example: "my-category".'),
                        'required' => true,
                        'validate' => 'isUrl',
                        'maxlength' => 128,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Meta Title'),
                        'name' => 'COMPSOULBLOG_CATEGORY_META_TITLE',
                        'desc' => $this->l('Enter the meta title for the main category.'),
                        'maxlength' => 255,
                    ),
                    array(
                        'type' => 'tags',
                        'lang' => true,
                        'label' => $this->l('Meta Keywords'),
                        'name' => 'COMPSOULBLOG_CATEGORY_META_KEYWORDS',
                        'desc' => $this->l('Enter keywords separated by commas.'),
                        'maxlength' => 255,
                    ),
                    array(
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->l('Description'),
                        'name' => 'COMPSOULBLOG_CATEGORY_META_DESCRIPTION',
                        'desc' => $this->l('Enter the meta description for the main category.'),
                        'cols' => 8,
                        'rows' => 4,
                        'maxlength' => 512,
                    ),
                    array(
                        'type' => 'message',
                        'name' => 'COMPSOULBLOG_FEATURED',
                        'desc' => '<div class="alert alert-info">'.$this->l('Edit the featured entries section.').'</div>',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Featured'),
                        'name' => 'COMPSOULBLOG_FEATURED_NUMBER',
                        'desc' => $this->l('Enter the number of articles displayed in the featured entries section.'),
                        'maxlength' => 2,
                        'inputmode' => 'numeric',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $languages = Language::getLanguages(false);
        $fields = [];

        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $id_root_category = 1;
            $root_category = $this->getCmsCategory($id_lang, $id_root_category);
            $root_category = (isset($root_category[0])) ? $root_category[0] : [];

            $fields['COMPSOULBLOG_CATEGORY_NAME'][$id_lang] = (isset($root_category['name']) ? $root_category['name'] : null);
            $fields['COMPSOULBLOG_CATEGORY_DESCRIPTION'][$id_lang] = (isset($root_category['description']) ? $root_category['description'] : null);
            $fields['COMPSOULBLOG_CATEGORY_LINK_REWRITE'][$id_lang] = (isset($root_category['link_rewrite']) ? $root_category['link_rewrite'] : null);
            $fields['COMPSOULBLOG_CATEGORY_META_TITLE'][$id_lang] = (isset($root_category['meta_title']) ? $root_category['meta_title'] : null);
            $fields['COMPSOULBLOG_CATEGORY_META_KEYWORDS'][$id_lang] = (isset($root_category['meta_keywords']) ? $root_category['meta_keywords'] : null);
            $fields['COMPSOULBLOG_CATEGORY_META_DESCRIPTION'][$id_lang] = (isset($root_category['meta_description']) ? $root_category['meta_description'] : null);
        }

        $fields['COMPSOULBLOG_FEATURED_NUMBER'] = Tools::getValue('COMPSOULBLOG_FEATURED_NUMBER', (int) Configuration::get('COMPSOULBLOG_FEATURED_NUMBER'));

        return $fields;
    }

    protected function postProcess()
    {
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $id_root_category = 1;
            $values = [];

            $values['name'] = Tools::getValue('COMPSOULBLOG_CATEGORY_NAME_' . $id_lang);
            $values['description'] = Tools::getValue('COMPSOULBLOG_CATEGORY_DESCRIPTION_' . $id_lang);
            $values['link_rewrite'] = Tools::getValue('COMPSOULBLOG_CATEGORY_LINK_REWRITE_' . $id_lang);
            $values['meta_title'] = Tools::getValue('COMPSOULBLOG_CATEGORY_META_TITLE_' . $id_lang);
            $values['meta_keywords'] = Tools::getValue('COMPSOULBLOG_CATEGORY_META_KEYWORDS_' . $id_lang);
            $values['meta_description'] = Tools::getValue('COMPSOULBLOG_CATEGORY_META_DESCRIPTION_' . $id_lang);

            $this->updateCmsCategory($values, $id_lang, $id_root_category);
        }

        Configuration::updateValue('COMPSOULBLOG_FEATURED_NUMBER', (int) Tools::getValue('COMPSOULBLOG_FEATURED_NUMBER'));

        $confirmation = $this->l('The settings have been updated.');

        return $this->displayConfirmation($confirmation);
    }

    protected function updateBlogImages($id_page = null)
    {
        $files = isset($_FILES["cms_page"]) ? $_FILES["cms_page"] : false;
        if ($files) {
            foreach ($files['tmp_name'] as $key => $value) {
                if(isset($files['tmp_name'][$key]) && !empty($files['tmp_name'][$key])) {
                    $file = [
                        "name" => (isset($files['name'][$key])) ? $files['name'][$key] : '',
                        "type" => (isset($files['type'][$key])) ? $files['type'][$key] : '',
                        "tmp_name" => (isset($files['tmp_name'][$key])) ? $files['tmp_name'][$key] : '',
                        "error" => (isset($files['error'][$key])) ? $files['error'][$key] : 0,
                        "size" => (isset($files['size'][$key])) ? $files['size'][$key] : 0,
                    ];

                    $this->updateImage($id_page, $file, $key);
                }
            }
        }
    }

    protected function updateImage($id_page, $file, $key)
    {
        $languages = Language::getLanguages(false);
        $id_default_lang = Configuration::get('PS_LANG_DEFAULT');

        $fileName = null;

        if ($error = ImageManager::validateUpload($file, 4000000)) {
            return $this->displayError($error);
        } else {
            $ext = substr($file['name'], strrpos($file['name'], '.') + 1);
            $name = md5($file['name']).'.'.$ext;

            if (!move_uploaded_file($file['tmp_name'], $this->getImgPath() . $name)) {
                return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
            } else {
                $entry = $this->getBlogEntry($id_default_lang, $id_page);
                if(isset($entry[$key]) && $entry[$key] != $name) {
                    @unlink($this->getImgPath() . $entry[$key]);
                }

                $fileName = $name;
            }
        }

        if($fileName) {
            foreach ($languages as $lang) {
                $id_lang = (int) $lang['id_lang'];
                $entry = $this->getBlogEntry($id_lang, $id_page);

                $entry[$key] = $fileName;
                $this->upsertBlogEntry($entry, $id_lang, $id_page);
            }
        }
    }

    protected function getBlogEntry($id_lang = null, $id_page = null)
    {
        $entries = $this->getBlogEntries($id_lang, $id_page);
        $entry = (isset($entries[0])) ? $entries[0] : [];
        return $entry;
    }

    protected function getBlogEntries($id_lang = null, $id_page = null)
    {
        if(!isset($id_lang) || !isset($id_page)) return [];

        $entries = Db::getInstance()->executeS((new DbQuery())
            ->select('*')
            ->from('compsoulblog')
            ->where('id_lang = ' . $id_lang)
            ->where('id_page = ' . $id_page)
        );

        return $entries;
    }

    protected function getBlogFeatured($limit = null, $order = null)
    {
        $id_lang = $this->context->language->id;

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('compsoulblog', 'b');
        $sql->innerJoin('cms', 'c', 'c.id_cms = b.id_page');
        $sql->innerJoin('cms_lang', 'l', 'c.id_cms = l.id_cms');
        $sql->where('l.id_lang = b.id_lang');

        $sql->where('b.id_lang = ' . (int) $id_lang);
        $sql->where('b.isBlog = 1');

        if ($limit) {
            $sql->limit((int) $limit);
        }

        if ($order) {
            $sql->orderBy($order);
        }

        return Db::getInstance()->executeS($sql);
    }

    protected function getBlogPages($id_lang = null, $id_page = null, $id_cms_categories = array(), $active = true)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('cms', 'c');

        $sql->innerJoin('cms_lang', 'l', 'c.id_cms = l.id_cms');
        $sql->innerJoin('compsoulblog', 'b', 'c.id_cms = b.id_page');

        $sql->where('l.id_lang = b.id_lang');
        $sql->where('b.isBlog = 1');

        if ($id_lang) {
            $sql->where('l.id_lang = ' . (int) $id_lang);
        }

        if ($id_page) {
            $sql->where('c.id_cms = ' . (int) $id_page);
        }

        if (!empty($id_cms_categories)) {
            $categories = implode(',', array_map('intval', $id_cms_categories));
            $sql->where('c.id_cms_category IN (' . $categories . ')');
        }

        if ($active) {
            $sql->where('c.active = 1');
        }

        $sql->orderBy('date_upd DESC');

        return Db::getInstance()->executeS($sql);
    }

    protected function getCmsCategories($id_lang)
    {
        $sql = new DbQuery();
        $sql->select('c.`id_cms_category`, cl.`name`, cl.`meta_title`');
        $sql->from('cms_category', 'c');
        $sql->leftJoin('cms_category_lang', 'cl', 'c.`id_cms_category` = cl.`id_cms_category`');
        $sql->where('cl.`id_lang` = ' . (int) $id_lang);
        $sql->orderBy('cl.`id_cms_category`');

        return Db::getInstance()->executeS($sql);
    }

    protected function getCmsCategory($id_lang, $id_category)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('cms_category_lang', 'cl');
        $sql->where('cl.`id_lang` = ' . (int) $id_lang);
        $sql->where('cl.`id_cms_category` = ' . (int) $id_category);

        return Db::getInstance()->executeS($sql);
    }

    protected function updateCmsCategory($data, $id_lang, $id_category)
    {
        $result = Db::getInstance()->update('cms_category_lang', $this->parametrize($data), 'id_lang =' . (int) $id_lang . ' AND id_cms_category =' . (int) $id_category);
    }

    protected function upsertBlogEntry($data, $id_lang, $id_page)
    {
        if ($this->issetBlogEntry($id_lang, $id_page)) {
            $data['date_upd'] = date('Y-m-d H:i:s');
            $result = Db::getInstance()->update('compsoulblog', $this->parametrize($data), 'id_lang =' . (int) $id_lang . ' AND id_page =' . (int) $id_page);
        } else {
            $result = Db::getInstance()->insert('compsoulblog', $this->parametrize($data));
        }
    }

    protected function parametrize($data)
    {
        $parametrized = array();
        foreach ($data as $key => $value) {
            $parametrized[$key] = pSQL($value, true);
        }

        return $parametrized;
    }

    protected function issetBlogEntry($id_lang, $id_page)
    {
        $result = (Db::getInstance())->executeS((new DbQuery())
            ->from('compsoulblog')
            ->select('Count(*)')
            ->where('id_lang = ' . (int)$id_lang)
            ->where('id_page = ' . (int)$id_page)
        );

        $count = array_shift($result[0]);

        return $count > 0;
    }

    protected function getImgPath()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
    }

    protected function getFrontImgPath()
    {
        return $this->context->link->getMediaLink(_MODULE_DIR_ . $this->name . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR .'img');
    }

    protected function getFieldDefinitions() {
        return array(
            'isBlog' => [
                'type' => SwitchType::class,
                'database' => 'tinyint(1) unsigned NOT NULL DEFAULT \'0\'',
                'options' => [
                    'label'    => $this->l('Is the entry part of a blog?'),
                    'required' => false,
                    'help' => $this->l('By selecting this option, the entry will appear in the appropriate category and in the list of entries.'),
                ],
            ],
            'preview' => [
                'type' => ImagePreviewType::class,
                'preview' => 'thumbnail',
            ],
            'thumbnail' => [
                'type' => FileType::class,
                'image' => true,
                'issetupdate' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'label' => $this->l('Thumbnail'),
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/jpg',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => $this->l('Please upload a valid image file (jpg, jpeg, png, webp).'),
                        ]),
                    ],
                ],
            ],
            'remove' => [
                'type' => CheckboxType::class,
                'remove' => 'thumbnail',
                'options' => [
                    'label'    => $this->l('Remove entry thumbnail'),
                    'required' => false,
                ],
            ],
            'summary' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => TextareaType::class,
                    'label' => $this->l('Summary'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('A summary of the news displayed in the list.'),
                    ],
                ],
            ],
            'headingFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Heading - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the main heading for the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'firstColumnHeadingFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('First Column Heading - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the first column in the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'firstColumnContentFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('First Column Content - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the first column in the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'secondColumnHeadingFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Second Column Heading - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the second column in the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'secondColumnContentFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('Second Column Content - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the second column in the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'thirdColumnHeadingFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Third Column Heading - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the third column in the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'thirdColumnContentFirstSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('Third Column Content - First Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the third column in the first section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'headingSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Heading - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the main heading for the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'firstColumnHeadingSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('First Column Heading - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the first column in the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'firstColumnContentSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('First Column Content - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the first column in the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'secondColumnHeadingSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Second Column Heading - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the second column in the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'secondColumnContentSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('Second Column Content - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the second column in the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'thirdColumnHeadingSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Third Column Heading - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the third column in the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'thirdColumnContentSecondSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('Third Column Content - Second Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the third column in the second section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'headingThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Heading - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the main heading for the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'firstColumnHeadingThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('First Column Heading - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the first column in the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'firstColumnContentThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('First Column Content - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the first column in the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'secondColumnHeadingThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Second Column Heading - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the second column in the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'secondColumnContentThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('Second Column Content - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the second column in the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'thirdColumnHeadingThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'varchar(255) NOT NULL',
                'options' => [
                    'type' => TextType::class,
                    'label' => $this->l('Third Column Heading - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the heading for the third column in the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
            'thirdColumnContentThirdSection' => [
                'type' => TranslatableType::class,
                'translatable' => true,
                'database' => 'longtext NOT NULL',
                'options' => [
                    'type' => FormattedTextareaType::class,
                    'label' => $this->l('Third Column Content - Third Section'),
                    'required' => false,
                    'options' => [
                        'help' => $this->l('Enter the content for the third column in the third section. Leave empty if not needed.'),
                    ],
                ],
            ],
        );
    }
}
