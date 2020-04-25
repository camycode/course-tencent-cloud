<?php

namespace App\Http\Web\Controllers;

use App\Caches\NavTreeList as NavTreeListCache;
use App\Caches\Setting as SettingCache;
use App\Library\Seo as SiteSeo;
use App\Services\Auth\Web as WebAuth;
use App\Traits\Response as ResponseTrait;
use App\Traits\Security as SecurityTrait;
use Phalcon\Mvc\Dispatcher;

class Controller extends \Phalcon\Mvc\Controller
{

    /**
     * @var SiteSeo
     */
    protected $siteSeo;

    /**
     * @var array
     */
    protected $siteSettings;

    /**
     * @var array
     */
    protected $siteNavs;

    /**
     * @var array
     */
    protected $authUser;

    use ResponseTrait, SecurityTrait;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->isNotSafeRequest()) {
            $this->checkHttpReferer();
            $this->checkCsrfToken();
        }

        $this->checkRateLimit();

        return true;
    }

    public function initialize()
    {
        $this->siteSeo = $this->getSiteSeo();
        $this->siteNavs = $this->getSiteNavs();
        $this->siteSettings = $this->getSiteSettings();
        $this->authUser = $this->getAuthUser();

        $this->siteSeo->setTitle($this->siteSettings['title']);

        $this->view->setVar('site_seo', $this->siteSeo);
        $this->view->setVar('site_navs', $this->siteNavs);
        $this->view->setVar('site_settings', $this->siteSettings);
        $this->view->setVar('auth_user', $this->authUser);
    }

    protected function getAuthUser()
    {
        /**
         * @var WebAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo() ?: [];
    }

    protected function getSiteNavs()
    {
        $cache = new NavTreeListCache();

        return $cache->get() ?: [];
    }

    protected function getSiteSettings()
    {
        $cache = new SettingCache();

        return $cache->get('site') ?: [];
    }

    protected function getSiteSeo()
    {
        return new SiteSeo();
    }

}
