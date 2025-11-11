{* smarty *}
{config_load file="site.conf"}
{load_presentation_object filename="store_front" assign="obj"}
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    {* title>{$obj->mPageTitle}</title> *}
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="{$obj->mSiteUrl}styles/tshirtshop.css" type="text/css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-light">
    <div id="doc" class="container-lg">
        <div id="bd">
            <div id="container-fluid-lg main d-flex flex-column">
                <div id="header" class="header py-3">
                    <a href="{$obj->mSiteUrl}" class="text-decoration-none text-dark">
                       <img src="{$obj->mSiteUrl}images/images/tshirtshop.png" alt="T-Shirt Shop Logo" class="me-3" />
                    </a>
                </div>
                <div class="d-flex gap-5">
                    <div class="d-flex flex-column">
                        <div id="left_column" class="me-4">
                            {include file="search_box.tpl"}
                        </div>
                        <div>
                            {include file="departments_list.tpl"}
                        </div>
                        <div>
                            {include file=$obj->mCategoriesCell}
                        </div>
                    </div>
                    <div id="contents" class="container-lg d-flex flex-column justify-content-center">
                        {include file=$obj->mContentsCell}
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>