<?php die("Access Denied"); ?>#x#a:2:{s:6:"result";a:5:{i:0;O:8:"stdClass":3:{s:4:"link";s:56:"https://virtuemart.net/news/494-bugfix-release-for-3-6-0";s:5:"title";s:24:"Bugfix release for 3.6.0";s:11:"description";s:4753:"<p>Implemented new restriction parameters provided by the VirtueMart core to our native payment plugins PayPal, Amazon Pay, Sofort, Authorize.net, eWay, heidelPay, Klarna, Skrill, 2checkout and Realex The latter also received a general update and has been renamed to 'globalpayments' because it&nbsp; was acquired by Global Payments Inc. some time ago.. There is a slight change in the handling of pending orders. The new procedure is described here: <a href="https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html">https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html</a></p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;">&nbsp;</p>
</div>
<h3>New Features</h3>
<ul>
<li>Added disabling of inherited related products and related categories</li>
<li>Customfields for shoppergroups</li>
<li>External media: Create thumbnails on the fly directly from remote server. Added extra permission for uploading remote media</li>
</ul>
<h3>enhanced or changed behaviour</h3>
<ul>
<li>Removed automatically selected ‘replace’ when selecting a media for upload</li>
<li>Removed keeping of customfield search filters when switching categories</li>
<li>Reconsidered the function deleteOldPendingOrder. The sql now always considers the time. New behaviour described here:<br /> <a href="https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html">https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html</a></li>
<li>Added message of missing/not writeable folder to the checkPath function</li>
<li>The customer_notified function now works only for the emails of the customer, the vendor email is always sent according to the orderstatus</li>
</ul>
<h3>Bugs</h3>
<ul>
<li>Fixed missing array key in getPayment</li>
<li>Fixed missing renderShipmentDropdown in shipment view</li>
<li>Taxes per bill were accidently not added to the shipment tax calculation</li>
<li>fixed overwrite prices in Paypal Express. Invalid token set the cart paymentmethod always to 0, even when paypal was not selected</li>
<li>Fixed creation of extra plugin tables of plugins textinput and specification</li>
<li>The vmplugin onStoreInstallPluginTable had replaced a $name against $this-&gt;name</li>
<li>Fixed breadcrumb for menu item pointing to productdetails. When menu item name and productname is the same, the productname is not written twice.</li>
<li>Added missing getDbo in state model (thx GJC)</li>
<li>Invoice view: Fixed foreach loop for the shipment address</li>
<li>Fixed a new (old) bug in order editing for the case discount before VAT</li>
</ul>
<h3>Completed</h3>
<ul>
<li>Added missing language</li>
<li>Updated vmprices.js so that it works also for quantity buttons in the cart (thank you Abhhishek)</li>
<li>Added country Montenegro</li>
<li>Safepath config model, added JPath clean before storing of the Path, added more check cases for wrong paths</li>
<li>Prices replaced init and step against data-init and data-step (the JS has a fallback)</li>
<li>Customer_notified works now only for the emails of the customer, the vendor email is always sent according to the orderstatus</li>
</ul>
<h3>Of Interest for developers</h3>
<ul>
<li>Important fix in cart helper function checkAutomaticSelectedPlug, the automaticSelected.type variable is now only set to true, if there is only one method.</li>
<li>Plugins using the core restriction remove automatically the xml vars with the same name. So we can easily write backward compatible payment/shipment plugins. Please read here&nbsp;<a href="http://docs.virtuemart.net/tutorials/development/240-important-adjustments-for-virtuemart-3-6.html">http://docs.virtuemart.net/tutorials/development/240-important-adjustments-for-virtuemart-3-6.html</a></li>
<li>In the vmdefines function defines, changed default from <em>site</em> to <em>0</em>, if 0 is used the appId is taken from joomla</li>
<li>Added resetting of categoryRecursed in router and category model before calling getCategoryRecurse removed unsed code</li>
<li>For the weight_countries shipment plugin, address type just by STsameAsBT only</li>
<li>For function getVendorCurrency added a fallback for empty vendorId and a vmTrace to find the problem <a href="http://forum.virtuemart.net/index.php?topic=141856.msg506893#msg506893">http://forum.virtuemart.net/index.php?topic=141856.msg506893#msg506893</a></li>
<li>Added function getSafePathFor, which gives and if applicable creates a path for a certain topic. Old function checkSafePath now creates automatically the invoice path</li>
</ul>";}i:1;O:8:"stdClass":3:{s:4:"link";s:62:"https://virtuemart.net/news/492-more-than-10000-committs-later";s:5:"title";s:34:"VirtueMart 3.6.0 is now available!";s:11:"description";s:12492:"<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM 3.6 NOW<br /> VirtueMart 3.6 component (core and AIO)</a></p>
</div>
<h2 style="text-align: justify;">More than 10000 commits later</h2>
<p style="text-align: justify;">With this version VirtueMart has exceeded the 10 000 code commits mark and approximately 150 commits have been integrated into VirtueMart since we released the last stable version of VirtueMart 3.4.x a while ago. In the meantime we have released some development and release candidate versions. Among the many improvements and small bug fixes, here are some of the more noteworthy changes:</p>
<h3 style="text-align: justify;">For Shop Owners</h3>
<p style="text-align: justify;">Enhancing the edit order feature was a very hard nut to crack and took much longer than expected. We did implement additional attributes like the "is Paid", which allows shop owners to see confirmed but unpaid orders, which is especially of interest for purchases on account and also for paid but refunded orders. There are also some features that will be rarely visible, e.g. an unpaid order for the order status "Refund" shows the message "not recommended" because unpaid and not yet delivered orders should simply be cancelled.</p>
<p style="text-align: justify;">The old function for displaying missing medias in the VirtueMart 'Media List', was just a filter over the loaded list. Previously it was only possible to search within the first 400 medias for missing ones. The problem here obviously was that the job can't be done just by an sql query. It must check the state of a media in the filesystem. So it was ‘impossible’ to list them on pages following the first one. The new function runs the query up to 1000 times, but stops when the first page is filled. Lets assume, the pagination is set to 30 and there are 500 missing medias within the first 30 000 medias, then it just displays the first 30. On the other hand, if the pagination is set to 400 and you have 399 missing media out of 400 000, you would see those missing 399 missing media.<br /><br />iStraxx donated a simplified version of their ‘Download Plugin’. It allows to download a file once. It is of course working with indirect links, which means the user gets a link pointing to the plugin and the plugin decides if the user gets a file.</p>
<h3 style="text-align: justify;">For Developers</h3>
<p style="text-align: justify;">Since we pay a lot of attention to the desires of plugin developers, shipment/payment methods can now use new restriction parameters provided by the core, which are very simple to implement. See our tutorial link for more details: <a href="https://docs.virtuemart.net/tutorials/development/236-update-payment-shipment-plugin-using-new-core-restrictions.html">https://docs.virtuemart.net/tutorials/development/236-update-payment-shipment-plugin-using-new-core-restrictions.html</a></p>
<p style="text-align: justify;">There is a major change in <em>vmAccess</em>. The new system checks for rights without checking a task only for admin/manager. When a task is given, it checks only for the task. The old system always checked against admin/manager plus given tasks.</p>
<p style="text-align: justify;">There are some new permissions and some have been removed. In the case of backend user input, the "vm.raw" and "vm.html" permission filters have been removed. We now use the Joomla validator for input.</p>
<p style="text-align: justify;">Spyros Petrakis added a nice feature: The category module can now be displayed using the menu seperator of joomla. Just use the desired menu and id as a class, for example&nbsp;mod_virtuemart_category-id.</p>
<h3 style="text-align: justify;">Future development</h3>
<p style="text-align: justify;">There are many ideas in the roadmap for VirtueMart. Currently we are working on a new frontend template with the very famous template developer "Virtueplanet" and a lot more is evolving in the background. At present we also need helping hands for the new backend template. Join our team! Becoming a team member is simple. Just demonstrate in our forum that you know what you are talking about and write a request here&nbsp;&nbsp;<a href="http://forum.virtuemart.net/index.php?board=139.0">http://forum.virtuemart.net/index.php?board=139.0</a></p>
<p style="text-align: justify;">Another idea is to make VirtueMart available for Wordpress users. We also need helping hands here. The idea is to use the Joomla libraries as much as possible, which is why we will also work on Joomla 4.</p>
<p style="text-align: justify;">Further ideas for the VirtueMart core are: Enhanced calculator, enhanced multichilds, enhanced display (more radios with images instead of dropdowns), adapting the joomla customfield system and so forth....</p>
<h3>Other changes</h3>
<h4>Shopowner:</h4>
<ul>
<li>new hidden config "hideEmptyCustomfields" hides empty customfields</li>
<li>search customfields, removed options of empty customfields</li>
<li>new switch "newBackendTemplate". Just add to the virtuemart.cfg <em>newBackendTemplate=1</em> to try the new backend template. Currently only the configuration view is updated.</li>
<li>translateable calculation rule names</li>
<li>vendor mail if order status changed has adjustable text per order status now COM_VIRTUEMART_MAIL_VENDOR_CONTENT_</li>
<li>removed storing of cart to table for normal cart calls</li>
<li>enhanced snippets.php for Searchengines</li>
<li>enhanced coupon listing,</li>
<li>added published to coupons</li>
<li>delivery address get automatically named with the zip and a random number</li>
<li>buttons for the add-to-cart popup</li>
<li>removed the second button group on top to avoid problems with captchas.</li>
<li>validateUserData, validation of the country/state works now also when the state is unpublished</li>
<li>fixed custom model, in case of wrong extension_id, updating now to the correct extension_id</li>
<li>attachment for emails: Very important fix, views are instances and so attached medias must be set to an empty array to prevent sending wrong attachments</li>
</ul>
<h5>Major updates for the cart</h5>
<ul>
<li>loading an old cart does not override already entered values</li>
<li>defaults are now correctly loaded not only into the fields but also into the cart address arrays</li>
<li>automatic selected ship/payment works now loading all available methods. Before it was only working if there was only one method left.</li>
<li>Fixed Paypal Smartbuttons</li>
<li>product added popup (padded.php) displays now actually added product amount (set in the cart helper)</li>
<li>product model, loading price should now consider the time offset correctly</li>
<li>removed dragndrop ordering for products, if there is more than one page (the js is not prepared for)</li>
<li>removed print view popup of invoices. It produced a different print, than by pdf.</li>
</ul>
<h4>Developer</h4>
<ul>
<li>new loading of Plugins to ensure correct execution sequence. In case of site, they are loaded directly in loadConfig. replaced JPluginHelper::importPlugin against VmConfig::importVMPlugins</li>
<li>Important fix for 404 handling to prevent loop when an unpublished category is browsed</li>
<li>fixed 404 in case controller was not found</li>
<li>moved the classes vmAccess, vmUri in extra files</li>
<li>moved Reporting/echo function in an own file</li>
<li>product model enhanced sql for omitted products</li>
<li>fixed json view for customplugins in edit customs, dropdowns were not correctly loaded and got stored with wrong default values</li>
<li>which lead to wrong default order status for some 3rd party plugins, therefor also a fix for the field orderstatus to load comma seperated default values correctly</li>
<li>new function getStrByAcl to get Strings from the Request by ACL</li>
<li>moved the price display of plugins in an own sublayout.</li>
<li>added new permission vm.user.editvendor, IMPORTANT the permission vm.user.editshop should be set for the shop owner respectivly the super admin only!</li>
<li>enhanced setStoreOwner function, which considers the multivendor configuration of the shop now</li>
<li>changed the initialising of the cart so, that it always loads the userfields with the default values first. Function prepareAddressFieldsInCart is now deprecated</li>
<li>extra checks in the table vmusers for user/vendor relation</li>
<li>replaced a continue in a switch against break for php7.3 compatibility</li>
<li>disabled loading of the jQuery framework from joomla completly if jQuery is disabled in the vm config (by Abhishek)</li>
<li>disabled loading of popup fancybox/facebox if jQuery is disabled (by Abhishek)</li>
<li>final update for TcPdf, <br /> a) updated paths, <br /> b) added an hint why there are no images and removed breaking rendering for missing images<br /> c) VmPdf got more error messages and should load the defines by itself, class VmVendorPDF is not anylonger wrapped in if(class_exists('TCPDF')){</li>
<li>replaced the file_get_contents against the modern JHttpFactory::getHttp</li>
<li>product details, fixed canonical URL of the parent product</li>
<li>address fields in the cart can now be initialized filled only with default values but without rendered html and js scripts</li>
<li>added variable to the cart, which keeps the values set by defaults to determine if a value is set by the user or the system</li>
<li>addJScript uses now the defer and async correctly again.</li>
<li>Important fix, related to svn 10005 and forum post <a href="http://forum.virtuemart.net/index.php?topic=141797.0-">http://forum.virtuemart.net/index.php?topic=141797.0-</a> Important fix, related to svn 10005 and forum post <a href="http://forum.virtuemart.net/index.php?topic=141797.0This">http://forum.virtuemart.net/index.php?topic=141797.0This</a> time, a missing customfield is directly added as array to the variantmods array</li>
</ul>
<h4>For Templaters</h4>
<ul>
<li>Invoice view, the address fields are now directly accessible, for example with&nbsp;echo $this-&gt;userfields['BT']['email']</li>
</ul>
<h5>Performance optimisations</h5>
<ul>
<li>userfield model country field, replaced direct sql by VmTableCountry (getTable,...)</li>
<li>payment/shipment methods, added cache and own function for getting shoppergroups. This prevents one call per plugin n the cart.</li>
<li>VmPlugin, added important cache for function selectedThisByMethodId</li>
<li>getPluginMethods is cached</li>
<li>router is now using the same parameters as used for product browsing, which saves again a lot sql (products are not extra loaded for router!).</li>
<li>product model added cached function getCurrentUserShopperGrps, function getProduct finds the CurrentUserShopperGrps itself before it needed to be called before the getProduct function. Same function used in getProductSingle and getProducts!</li>
<li>enhanced the checkIfCached function, so the router can most time find an already loaded product</li>
<li>model customfields, enhanced also the cache of the function getCustomEmbeddedProductCustomFields</li>
<li>optimization for the functions getCountryByID and getCountryIDByName and the country model, loading a country creates directly multiple keys for the cached entry.</li>
<li>added important improvement, removed random in sql, the randomizing of products is done by loading more than needed an array_shuffle</li>
<li>router uses now also the cached Table class (reduced sql drastically)</li>
<li>added autohashing of tables (if set in the constructor)</li>
<li>model userfields replaced single requests in function getIfRequired against one but cached request.</li>
<li>Fixed caching in customfields model, loading a product twice with different quantities loaded the customfields plus the cached ones</li>
<li>Caching in model state functions getStates and testStateCountry</li>
<li>Caching in mediahandler function getIcon</li>
<li>getVmPluginMethod set false as default for parameter cache</li>
</ul>
<p>...and many other minor features, fixes, corrections. See the complete list of commits at <a href="http://dev.virtuemart.net/projects/virtuemart/repository">http://dev.virtuemart.net/projects/virtuemart/repository</a></p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM 3.6 NOW<br /> VirtueMart 3.6 component (core and AIO)</a></p>
</div>";}i:2;O:8:"stdClass":3:{s:4:"link";s:52:"https://virtuemart.net/news/491-bugfix-release-3-4-2";s:5:"title";s:20:"Bugfix release 3.4.2";s:11:"description";s:1342:"<p>This release primarily fixes a bug which affected some users when they updated their shop to VirtueMart 3.4.0. Third party developers should update their systemplugins loading the VirtueMartConfig class analogue to the virtuemart system plug-in. For details please use this <a href="http://forum.virtuemart.net/index.php?topic=141175.msg496861#msg496861">link to the forum thread</a></p>
<p>Also VirtueMart 3.4.2 gives users the oppportunity to test PayPal Smart Buttons. PayPal Smart Buttons offer several style options to customize the look and feel of your smart payment button. You can also use options to display multiple funding sources to the buyer, when appropriate. It is very easy to configure and deprecates the simple "PayPal Exress" and "PayPal Credit" options.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;">&nbsp;</p>
</div>
<p>Please read here for the complete news <a href="https://virtuemart.net/news/490-virtuemart-3-4-prepare-for-the-future">Virtuemart 3.4 prepare for the future</a> and here for the concrete list of changes from vm3.4.0 to vm3.4.1 <a href="https://forum.virtuemart.net/index.php?topic=141175.0">List of fixes</a></p>";}i:3;O:8:"stdClass":3:{s:4:"link";s:69:"https://virtuemart.net/news/490-virtuemart-3-4-prepare-for-the-future";s:5:"title";s:37:"Virtuemart 3.4 prepare for the future";s:11:"description";s:13979:"<p>This release is now ready for all our VirtueMart users.</p>
<p>Due to the wide ranging changes we have made to underlying core functions and the removal of old VirtueMart and Joomla compatibility (which was weighing things down and slowing future developments), we have taken more time putting this release together than usual. Initial feedback from our beta testers has shown us that it has been worth the extra effort and time that it took.</p>
<p><strong>Here are some highlights:-</strong></p>
<p><b>Improved core</b> - VirtueMart core now benefits from using the Joomla classloader providing a more performant and more failproof method for classloading (classes are registered automatically and loaded if need.)</p>
<p><b>Ready for Joomla 3.9</b> - The core is ready for Joomla 3.9 and we expect that it will be relatively simple and fast to adapt for Joomla 4</p>
<p><b>PHP 7.2 - compatible</b> - VirtueMart 3.4 is now php7.2 compatible, users can now benefit from more secure and faster PHP versions.</p>
<p><b>Javascript updates</b> - We started to rewrite the javascripts to use data-vm instead of classes or ids - fallbacks are provided.</p>
<p><b>VirtueMart Package improvements</b> - This now automatically installs the VirtueMart Core, AIO, vmBeez3 template and the TCPDF. The installers within the package can still be used individually for those not requiring the full installation.</p>
<p><b>Next release in progress</b> - Expect to see two new payments, eWay and PayPal's “Smart Buttons”. A new template option which loads for example layouts with a bs4 prefix allows us to develop a completely new frontend template, whilst keeping backward compatibility. We are also planning for a new backend template, for which suggestions are welcome in the forum.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;">&nbsp;</p>
</div>
<h2>Enhancements</h2>
<p><strong>PHP 7.1/2 support</strong></p>
<ul>
<li>Encryption and decryption with openssl for PHP 7.2 compatibility.</li>
</ul>
<p><strong>Order model changes</strong></p>
<ul>
<li>New trigger plgVmOnUpdateSingleItem.</li>
<li>Extra variable $inputOrder to the old triggers plgVmOnUpdateOrderShipment and plgVmOnUpdateOrderPayment.</li>
</ul>
<p><strong>Products</strong></p>
<ul>
<li>New feature "maximum products", "maximum customers" per vendor and "force product pattern".</li>
<li>Product is loaded for an order even when unpublished.</li>
<li>New filter for customprototypes in the product listing.</li>
<li>Products can be assigned a fixed Canonical Category – useful where a product is in multiple categories and the category name forms part of the product URL - product_canon_category_id.</li>
<li>Admin product list - A bulleted list for categories is now shown.&nbsp; Canonical category is highlighted.</li>
<li>Product model, getProduct, customfields are always loaded.</li>
<li>For FE search of items - the product model no longer replaces the search character “-“ with “%”.</li>
<li>Table change to products_language tables, “product_desc” now set as Datatype “text” (no varchars any longer).</li>
</ul>
<p><strong>Customfields</strong></p>
<ul>
<li>Admin feature to transform a set default list of "S" customfield to another and updates the values in the products that use the transformed customfield. transformSetStringsList.</li>
<li>Customfields can have the same name using a hidden configuration “unique_customfield_titles” to disable “unique names”.</li>
<li>New submenu to Joomla to the customfields list.</li>
<li>New method to calculate the variant price inside the function "getProductPrice" just using TRUE&nbsp;for the second parameter instead of a float&nbsp;</li>
</ul>
<p><strong>Currency handling</strong></p>
<ul>
<li>Calculation of net price rounds final price first (prevents wrong inputs) – calculatorH.</li>
<li>CurrencyDisplay roundForDisplay we round first before we multiply by quantity (as in the calculator), but not in Rappenrounding mode.</li>
<li>Rounding in CurrencyDisplay uses "round only display config".</li>
<li>Small change of rounding in currencydisplay using a different currency.</li>
<li>New option shared to currency admin views. (Program logic is maybe not complete).</li>
</ul>
<p><strong>Image handling</strong></p>
<ul>
<li>Images createThumb is only executed, when file not available. Forcing of thumb creation is done by deletion.</li>
<li>Search for unused media in admin.</li>
</ul>
<p><strong>User switch by admin FE</strong></p>
<ul>
<li>New hidden config switches:</li>
<li>ChangeShopperAlsoUseAdminShoppergroups - Add the shoppergroups of the logged in Admin user to the “switched” user’s shoppergroups.</li>
<li>ChangeShopperDeleteCart - When a user is chosen by the admin – the cart contents are cleared – prevents accidental inclusion of cart items.</li>
</ul>
<p><strong>Orders and checkout</strong></p>
<ul>
<li>Cart handling a change of the quantity is included in the popup and as extra warning message in the cart.- checkForQuantities changed vmInfo from vmWarn.</li>
<li>The layout ‘padded’ has a small update to show all quantity warnings in the popup.</li>
<li>products can use html classes in the cart item row</li>
<li>Checkout user data is stored, even when the userfield validation fails (the validation is for the checkout process).</li>
<li>Some work on the $cart-&gt;orderdoneHtml = $html; thematic (in vmpsplugin.php).</li>
<li>Admin Order list, more intuitive sequence for the columns.</li>
<li>Order editing will only store a Ship To address when STsameAsBT is empty.&nbsp; New order variable- STsameAsBT.&nbsp; With a new checkbox to control the addition of a ST address in order edit.</li>
</ul>
<p><strong>General</strong></p>
<ul>
<li>Added JRoute to action of the user edit form in FE.</li>
<li>Added filter vendors to user list.</li>
<li>Captcha for vendor contact form.</li>
<li>Shop configuration for FE views "set bootstrap layout version X", which adds a prefix for example bs2- for loaded layouts.</li>
<li>Added function "alt" to vmText.</li>
</ul>
<h1>Modifications</h1>
<ul>
<li>Important update for VirtueMart System plugin. It tries to load the configuration file of the installer and not the already installed one.</li>
<li>Exchanged hard coded string against vmText.</li>
<li>Spaces to Tab and indentation.</li>
<li>Replaced all id="vm. with id="vm-</li>
<li>Moved the js validation and setting the chosen dropdowns to required in an extra file.</li>
<li>Removed double id="reg_text", replaced with “class.</li>
<li>Language string change in de-DE.com_virtuemart_config.ini and en-GB.com_virtuemart_config.ini.</li>
<li>Removed mootools from vmbeez3 template.</li>
<li>Added plugintype vmextended to whitefilter of controllers/plugin.php.</li>
<li>Membership checker shows error in ajax request (simpler to debug).</li>
<li>Added JRoute to product link in sublayout products.</li>
<li>js using data-vm="product-container" instead of classes, fallback provided.</li>
<li>js now uses data-vm, all dependencies to classes will be removed soon. Fallbacks provided.</li>
<li>vmpsplugin.PHP methods which cannot be selected are now unset from the array of available methods.</li>
<li>vmplugin function _getLayoutPath is not public and static.</li>
<li>getMyOrderDetails, changed unused 3rd parameter. It sets now if the config should be considered for ordertracking. Some 3rd parties need it.</li>
<li>New fallback for product customfields, when the cart is loaded and had no data in the session.</li>
<li>Changed reload=1 attribute to data-reload=1 (with fallback in js for the old reload=1).</li>
<li>Changed activation text in registration email, when set to "activation by administrator".</li>
<li>prepareViewForMail now uses the generic controller, not a specific one (could make trouble with Admin and FE controllers having the same name).</li>
<li>Cart helper checkAutomaticSelectedPlug, when no method is available the method_id is set to 0.</li>
<li>Files of extensions are directly copied from temp directory to the correct place.</li>
<li>Product_name is now handled in the controller as the other special input fields and follows the ACL for writing raw/HTML or just normal text.</li>
<li>Added to getInstance of the calculationHelper the parameters vendorId, countryId and stateId.</li>
<li>Test for country/state improved. Added differentiation between valid and require.</li>
<li>Improve performance of calculationHelper function setCountryState using a new pattern to load the country and state of the registered user.</li>
<li>Added hidden feature, "directCheckout", which directly starts the checkout process with redirect</li>
</ul>
<h2>Fixes</h2>
<ul>
<li>URL of currency_converter/convertECB.php must use https now.</li>
<li>Cart object small fix which prevents overriding of $customProductData, when trigger plgVmOnAddToCartFilter is used.</li>
<li>Important fix for correct order status for order history.</li>
<li>Important fix for order editing was causing wrong calculation results - replaced product_item_price for product_discountedPriceWithoutTax for calculation of the subtotal.</li>
<li>Checkbox cartfields are now correctly stored in the order.</li>
<li>Fixed return value of function CreateOrderHead <a href="http://forum.virtuemart.net/index.php?topic=140616.0.">http://forum.virtuemart.net/index.php?topic=140616.0.</a></li>
<li>Added $view-&gt;mediaToSend = array(); in function sendVmMail to prevent sending of cached medias in order status update emails.</li>
<li>Fix for order_status vs order_status_code.</li>
<li>heidelpay, small fixes and changes.</li>
<li>PayPal hosted, fixed currency.</li>
<li>PayPal hosted payment iframe little catch for EMAILLINK – handles no PayPal response.</li>
<li>Standard payment: fix in tmpl.</li>
<li>Standard payment: update order status now happens before orderdone view rendering.</li>
<li>eway: fix the CVN in case of cart saved fix invoiceDescription.</li>
<li>authorize.net plgVmOnShowOrderFEPayment changed to public <a href="http://forum.virtuemart.net/index.php?topic=133563.msg492466#msg492466.">http://forum.virtuemart.net/index.php?topic=133563.msg492466#msg492466.</a></li>
<li>Fix in config.php typo in JLoader::register, creditcart.php to creditcard.php.</li>
<li>Correct storing of customplugins.</li>
<li>plgVmOnStoreInstallPluginTable of specification plugin.</li>
<li>Links to shoppergroups in ship-/payment methods listing.</li>
<li>Text in Virtuemart Search Module doesn't clear&nbsp; <a href="https://forum.virtuemart.net/index.php?topic=139961.0.">https://forum.virtuemart.net/index.php?topic=139961.0.</a></li>
<li>Small fix in admin product edit, which prevents removing the categories if a product is stored before the category tree was loaded.</li>
<li>Problem with not loaded parent categories in product detail.</li>
<li>Minor errors and typos (for example a note thrown cloning a product (thx Patrick K.).</li>
<li>Category cache.</li>
<li>Sublayout customfield used duplicate keys.</li>
<li>Corrected small typo in en-GB.mod_virtuemart_product.ini.</li>
<li>Fixed some Language translation issues.</li>
<li>Updated de-DE.mod_virtuemart_product.ini.</li>
<li>replaced JFactory::getLanguage against vmLanguage::getLanguage.</li>
</ul>
<h1>Minors</h1>
<ul>
<li>vmstore template foundation.</li>
<li>Added deletion of Media synchronization progress, when finished..</li>
<li>Removed old VM_VERSION (j2.5 compatibility).</li>
<li>Removed more DS, also for paths, added vRequest::filterPath().</li>
<li>Joomla Fullinstaller.</li>
<li>Removed unused files.</li>
<li>Replaced old JError against exception.</li>
<li>Added missing license notes.</li>
<li>Some old JRaiseError, JREQUEST_ALLOWHTML (also from old comments).</li>
<li>Removed unused error reporting(0); in Admin/views/orders/view.raw.php.</li>
<li>Removed old if !class_exists require's.</li>
<li>Install script.virtuemart.php removed old j1.6 legacy.</li>
<li>PayPal updated xml field to vmfile to vmfiles.</li>
<li>Updated vmbeez install file using the method "upgrade".</li>
<li>AIO installer replaced is_dir against JFolder::exists to prevent false positive error message.</li>
<li>Removed some more DS, the remaining DS are meant for realpath, which could be outside of the webfolder.</li>
<li>Textinput plugin, removed old trigger.</li>
<li>vmuploader, failing the joomla upload filter returns false.</li>
<li>Enhanced message when no vendor currency is defined.</li>
<li>Removed unnecessary language keys. Languages keys which are used in a special language file should never appear in the default component language file.</li>
<li>Package installer: added fallback in vmplugin to get the xml file from the default folder in case the temporary install folder fails.</li>
<li>Package installer: added check in updatesmigration.php if xml is available.</li>
<li>Use an internal variable html, to display the messages. It is echoed for normal install and put in the Request for package install.</li>
<li>Added template to package.</li>
<li>Updated js of the template.</li>
<li>Vendor list is now sending the form and ordered by name.</li>
<li>Changed function getUserList using user_is_vendor instead of is_vendor.</li>
</ul>
<p>TCPDF</p>
<ul>
<li>Moved tcpdf files to libraries/vendor/tecnickcom/ and libraries/src/Document following the new file structure of Joomla.</li>
<li>Now also deletes the old "libraries" folder in the be folder in case it exists.</li>
<li>Removed old j2.5, j1.6 stuff and DS.</li>
<li>Replaced DS for /.</li>
<li>Fixed wrong path in getTCPDFFontsList.</li>
<li>Fixed typo in vmpdf.</li>
</ul>
<h1>Work in progress</h1>
<ul>
<li>Vendor model another idea to handle a multicurrency store (would mean to create invoices in the user selected currency).</li>
<li>Better refund of invoices</li>
</ul>";}i:4;O:8:"stdClass":3:{s:4:"link";s:96:"https://virtuemart.net/news/489-virtuemart-3-2-14-security-release-and-enhanced-invoice-handling";s:5:"title";s:66:"VirtueMart 3.2.14 - Security Release and enhanced invoice handling";s:11:"description";s:2566:"<p>This fairly serious XSS discovered by Mattia Furlani pertained only the administration area, so most shops are not affected. Shop owners running a multi-vendor store or fearing that their employees may use this leak should update as soon as possible.</p>
<p>The new core has some fixes for php 7.1 - 7.2 compatibility.</p>
<h3>Compliance to the new french financial law</h3>
<p>At present we have also integrated some fraud protection requirements to comply with the new French law. This includes, for example, the new invoice processing system. When an invoice was changed, the old treatment renamed the originally created invoice and created a new invoice with the same invoice number. The new treatment creates a regular new invoice number while the old invoice remains listed and accessible. We also added an order item history table. The class vmtable can now automatically save a hash to any entry. For example the order entries store a hash of the important data per line, so it is now possible to check the integrity of an entry. This system is not completed yet.</p>
<h2>Further features:</h2>
<ul>
<li>Behaviour of the table object is more consistent and reliable.</li>
<li>Behaviour of payment plugins after pressing confirm in the cart and cancelling the payment is now more consistent.</li>
<li>Removed w3c validation errors.</li>
<li>Corrected routing for orderdone layout.</li>
<li>Trigger 'plgVmAfterStoreProduct', added array key "new" to $data, so that we know if a product is new or just updated.</li>
<li>Customfield date has now two extra parameters to set the initial date and year range. The initial date uses as format DateInterval, so the P0D means use the current.</li>
<li>Language files updated.</li>
<li>Long desired fix, dropdowns of prices in product edit work now directly.</li>
<li>Enhanced handling of the orderdone layout.</li>
<li>Minor compatibility enhancements of javascript and html.</li>
<li>_triesValidateCoupon is now emptied after entering a valid coupon.</li>
<li>Coupons are not automatically removed any longer when expired.</li>
<li>Full installer now also works with multilingual setup.</li>
</ul>
<p>The full list is available here <a href="http://forum.virtuemart.net/index.php?topic=139652.msg490664">http://forum.virtuemart.net/index.php?topic=139652.msg490664</a></p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;"> </p>
</div>";}}s:6:"output";s:0:"";}