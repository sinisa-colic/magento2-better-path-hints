# magento2-better-path-hints
### Better path hints for Magento 2
### Please use this only for development in local instances, as using it has negative effects on security and performance.
Wraps every block/container with HTML comment with information related to it.
Example:
```HTML
<!-- BLOCK-f2935 name='page.main.title' parent='columns.top' class='Magento\Theme\Block\Html\Title' template='vendor/magento/module-theme/view/frontend/templates/html/title.phtml'-->
<div class="page-title-wrapper">
    <h1 class="page-title">
        <span class="base" data-ui-id="page-title-wrapper">Home Page</span>
    </h1>
</div>
<!-- /BLOCK-f2935 name='page.main.title' -->
...
<!-- CONTAINER-03360 name='page.messages' parent='columns.top'  -->
<div class="page messages">...</div>
<!-- /CONTAINER-03360 name='page.messages' -->
```
Installation:
```sh
composer require magerules/module-betterpathhints:dev-master
bin/magento module:enable MageRules_BetterPathHints
bin/magento setup:upgrade
```
