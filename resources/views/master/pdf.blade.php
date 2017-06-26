<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<div class="content">
    @yield('content')

</div>

<style>
    .page-break {
        page-break-after: always;
    }

    .template-logo {
         max-width: 200px;
         max-height: 100px;
    }
    #template-header-wrapper {
        text-align: center;
        font-family: "Times New Roman", Times, serif;
        width: 100%;
    }

    #template-header-wrapper address {
        padding-top: 10px;
        padding-bottom: 20px;
        font-size: 16px;
    }

    .content {
        font-family: Helvetica, Arial, sans-serif, Verdana;
        font-size: 14px;
    }

    @page{
        margin: 72px;
    }

    .document-stamp {
        font-size: 10px;
        position: fixed;
        bottom: -20px;
        right: -20px;
        text-align: right;
    }

</style>