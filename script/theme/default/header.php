<?php defined("APP") or die() ?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <title>GD Player Script | 2020</title>
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
      <meta name="msapplication-TileColor" content="#206bc4"/>
      <meta name="theme-color" content="#206bc4"/>
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
      <meta name="apple-mobile-web-app-capable" content="yes"/>
      <meta name="mobile-web-app-capable" content="yes"/>
      <meta name="HandheldFriendly" content="True"/>
      <meta name="MobileOptimized" content="320"/>
      <meta name="robots" content="noindex,nofollow,noarchive"/>
      <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
      <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon"/>
      <!-- Libs CSS -->
      <link href="<?=getThemeURI()?>/assets/libs/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/selectize/dist/css/selectize.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/fullcalendar/core/main.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/fullcalendar/daygrid/main.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/fullcalendar/timegrid/main.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/fullcalendar/list/main.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/libs/nouislider/distribute/nouislider.min.css" rel="stylesheet"/>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>
      <!-- Tabler Core -->
      <link href="<?=getThemeURI()?>/assets/css/tabler.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/custom.css" rel="stylesheet"/>
      <!-- Tabler Plugins -->
      <link href="<?=getThemeURI()?>/assets/css/tabler-flags.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/tabler-payments.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/tabler-buttons.min.css" rel="stylesheet"/>
      <link href="<?=getThemeURI()?>/assets/css/demo.min.css" rel="stylesheet"/>
      <style>
         body {
         display: none;
         }
      </style>
   </head>
   <body class="antialiased <?php if($this->config['dark_theme'] == 1) echo 'theme-dark'; ?>">
      <div class="page">
      <header class="navbar navbar-expand-md navbar-dark">
         <div class="container-xl">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
            </button>
            <a href="<?=PROOT?>" class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pr-0 pr-md-3">
               <!-- <img src="<?=getThemeURI()?>/logo.svg" alt="Tabler" class="navbar-brand-image"> -->
               <h1 class="logo-txt"> <b>GD</b>  <i>player</i> <sup style="font-size: 18px;">v1.5</sup> </h1>
            </a>
            <div class="navbar-nav flex-row order-md-last">
               <div class="nav-item dropdown">
                  <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown">
                     <span class="avatar" style="background-image: url(./static/avatars/000m.jpg)"></span>
                     <div class="d-none d-xl-block pl-2">
                        <div>Hello, <?php if(isset($_SESSION['user'])) echo ucwords($_SESSION['user']); ?></div>
                        <?php if ($this->isAdmin): ?>
                        <div class="mt-1 small text-muted">administration</div>
                        <?php else: ?>
                        <div class="mt-1 small text-muted">user</div>
                        <?php endif; ?>
                     </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                     <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#modal-logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z"/>
                           <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                           <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                           <line x1="16" y1="5" x2="19" y2="8" />
                        </svg>
                        Logout
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <div class="navbar-expand-md">
         <div class="navbar collapse navbar-collapse navbar-light" id="navbar-menu">
            <div class="container-xl">
               <ul class="navbar-nav">
                  <li class="nav-item">
                     <a class="nav-link" href="<?=PROOT?>/dashboard" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"/>
                              <polyline points="5 12 3 12 12 3 21 12 19 12" />
                              <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                              <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Dashboard
                        </span>
                     </a>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#navbar-extra" data-toggle="dropdown" role="button" aria-expanded="false" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                              <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                              <line x1="16" y1="21" x2="16" y2="19"></line>
                              <line x1="19" y1="16" x2="21" y2="16"></line>
                              <line x1="3" y1="8" x2="5" y2="8"></line>
                              <line x1="8" y1="3" x2="8" y2="5"></line>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Links
                        </span>
                     </a>
                     <ul class="dropdown-menu">
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/links/new" >
                           Add new link
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/links/active" >
                           Active Links
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/links/deleted" >
                           Deleted Links
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/links/broken" >
                           Broken Links
                           </a>
                        </li>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/links/bulk-import" >
                           Bulk Import
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#navbar-extra" data-toggle="dropdown" role="button" aria-expanded="false" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <circle cx="12" cy="7" r="4"></circle>
                              <path d="M5.5 21v-2a4 4 0 0 1 4 -4h5a4 4 0 0 1 4 4v2"></path>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Users
                        </span>
                     </a>
                     <ul class="dropdown-menu">
                        <?php if ($this->isAdmin): ?>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/users/add" >
                           Add User
                           </a>
                        </li>
                        <?php endif; ?>
                        <li >
                           <a class="dropdown-item" href="<?=PROOT?>/users" >
                           All Users
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="<?=PROOT?>/ads"  >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"></path>
                              <path d="M12 3v3m0 12v3"></path>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Advertisement
                        </span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="<?=PROOT?>/settings"  >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                              <circle cx="12" cy="12" r="3"></circle>
                           </svg>
                        </span>
                        <span class="nav-link-title">
                        Settings
                        </span>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
