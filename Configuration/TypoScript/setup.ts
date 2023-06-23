# mobile nav ajax handler
upaintAjax = PAGE
upaintAjax {
    typeNum = 20211130

    # add plugin
    10 = USER
    10 {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = Upaint
        pluginName = Ajaxhandler
        vendorName = Mattgold
        controller = Frontend
    }

    # disable header code
    config {
        disableAllHeaderCode = 1
        additionalHeaders = Content-type: application/json, utf-8

        no_cache = 0

        xhtml_cleaning = 0
        admPanel = 0
        debug = 0
    }
}