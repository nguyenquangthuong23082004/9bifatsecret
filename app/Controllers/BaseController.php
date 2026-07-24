<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        $this->helpers = ['common', 'url', 'html', 'asset'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $session = service('session');
        $lang = $session->get('lang') ?? 0;
        $locale = ($lang == 1) ? 'en' : 'ko';
        $request->setLocale($locale);
        
        // Share $_lang and settings for views
        $site_settings = [];
        $popups = [];
        try {
            $settingModel = new \App\Models\Setting();
            $site_settings = $settingModel->getSettings();
            $popups = $this->getActivePopups($lang);
        } catch (\Throwable $e) {
            // Fallback if database is not connected
        }

        service('renderer')->setData([
            '_lang'    => $lang,
            '_settings' => $site_settings,
            '_popups'  => $popups,
        ]);
    }

    /**
     * Returns active popups based on current language and schedule.
     */
    private function getActivePopups(int $lang): array
    {
        try {
            $db = \Config\Database::connect();
            $langType = ($lang == 1) ? 'en' : 'kr';
            $now = date('Y-m-d H:i');

            $sql = "SELECT * FROM tbl_popup
                    WHERE P_TYPES = ?
                      AND status != 'C'
                      AND (
                            status = 'B'
                            OR (
                                status = 'A'
                                AND CONCAT(P_STARTDAY,' ',LPAD(P_START_HH,2,'0'),':',LPAD(P_START_MM,2,'0')) <= ?
                                AND CONCAT(P_ENDDAY,' ',LPAD(P_END_HH,2,'0'),':',LPAD(P_END_MM,2,'0')) >= ?
                            )
                      )
                    ORDER BY idx ASC";

            return $db->query($sql, [$langType, $now, $now])->getResultArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
