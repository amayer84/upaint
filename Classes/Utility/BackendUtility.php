<?php
declare(strict_types=1);
namespace Mattgold\Upaint\Utility;

use TYPO3\CMS\Backend\Routing\Exception\ResourceNotFoundException;
use TYPO3\CMS\Backend\Routing\Router;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;

/**
 * Class BackendUtility
 */
class BackendUtility
{

    /**
     * Check if backend user is admin
     *
     * @return bool
     */
    public static function isBackendAdmin()
    {
        if (isset(self::getBackendUserAuthentication()->user)) {
            return self::getBackendUserAuthentication()->user['admin'] === 1;
        }
        return false;
    }

    /**
     * Get property from backend user
     *
     * @param string $property
     * @return string
     */
    public static function getPropertyFromBackendUser($property = 'uid')
    {
        if (!empty(self::getBackendUserAuthentication()->user[$property])) {
            return self::getBackendUserAuthentication()->user[$property];
        }
        return '';
    }

    /**
     * @return BackendUserAuthentication
     */
    public static function getBackendUserAuthentication()
    {
        return parent::getBackendUserAuthentication();
    }

    /**
     * Create an URI to edit any record
     *
     * @param string $tableName
     * @param int $identifier
     * @param bool $addReturnUrl
     * @return string
     */
    public static function createEditUri($tableName, $identifier, $addReturnUrl = true)
    {
        $uriParameters = [
            'edit' => [
                $tableName => [
                    $identifier => 'edit'
                ]
            ]
        ];
        if ($addReturnUrl) {
            $uriParameters['returnUrl'] = self::getReturnUrl();
        }

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return $uriBuilder->buildUriFromRoute('record_edit', $uriParameters)->__toString();
    }

    /**
     * Create an URI to add a new record
     *
     * @param string $tableName
     * @param int $pageIdentifier where to save the new record
     * @param bool $addReturnUrl
     * @return string
     */
    public static function createNewUri($tableName, $pageIdentifier, $addReturnUrl = true)
    {
        $uriParameters = [
            'edit' => [
                $tableName => [
                    $pageIdentifier => 'new'
                ]
            ]
        ];
        if ($addReturnUrl) {
            $uriParameters['returnUrl'] = self::getReturnUrl();
        }
        
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return $uriBuilder->buildUriFromRoute('record_edit', $uriParameters)->__toString();
    }

    /**
     * Get return URL from current request
     *
     * @return string
     */
    protected static function getReturnUrl()
    {
        return self::getModuleUrl(self::getModuleName(), self::getCurrentParameters());
    }

    /**
     * Get module name or route as fallback
     *
     * @return string
     */
    protected static function getModuleName()
    {
        $moduleName = 'web_layout';
        if (GeneralUtility::_GET('M') !== null) {
            $moduleName = (string)GeneralUtility::_GET('M');
        }
        if (GeneralUtility::_GET('route') !== null) {
            $routePath = (string)GeneralUtility::_GET('route');
            $router = GeneralUtility::makeInstance(Router::class);
            try {
                $route = $router->match($routePath);
                $moduleName = $route->getOption('_identifier');
            } catch (ResourceNotFoundException $exception) {
                unset($exception);
            }
        }
        return $moduleName;
    }

    /**
     * Get all GET/POST params without module name and token
     *
     * @param array $getParameters
     * @return array
     */
    public static function getCurrentParameters($getParameters = [])
    {
        if (empty($getParameters)) {
            $getParameters = GeneralUtility::_GET();
        }
        $parameters = [];
        $ignoreKeys = [
            'M',
            'moduleToken',
            'route',
            'token'
        ];
        foreach ($getParameters as $key => $value) {
            if (in_array($key, $ignoreKeys)) {
                continue;
            }
            $parameters[$key] = $value;
        }
        return $parameters;
    }

    /**
     * Returns the URL to a given module
     *      mainly used for visibility settings or deleting
     *      a record via AJAX
     *
     * @param string $moduleName Name of the module
     * @param array $urlParameters URL parameters that should be added as key value pairs
     * @return string Calculated URL
     */
    public static function getModuleUrl($moduleName, $urlParameters = [])
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return $uriBuilder->buildUriFromRoute($moduleName, $urlParameters)->__toString();
    }

    /**
     * Returns the Page TSconfig for page with id, $id
     *
     * @param int $pid
     * @param array $rootLine
     * @param bool $returnPartArray
     * @return array Page TSconfig
     * @see \TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser
     */
    public static function getPagesTSconfig($pid, $rootLine = null, $returnPartArray = false)
    {
        $array = [];
        try {
            // @extensionScannerIgnoreLine Seems to be a false positive: getPagesTSconfig() still need 3 params
            $array = BackendUtilityCore::getPagesTSconfig($pid, $rootLine, $returnPartArray);
        } catch (\Exception $exception) {
            unset($exception);
        }
        return $array;
    }

    /**
     * @return bool
     */
    public static function isBackendContext()
    {
        return TYPO3_MODE === 'BE';
    }
}
