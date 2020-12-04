<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <!-- Tabler Core -->
      <link href="<?=getThemeURI()?>/assets/css/tabler.min.css" rel="stylesheet"/>
      <title></title>
   </head>
   <body class="antialiased border-top-wide border-primary d-flex flex-column">
      <div class="flex-fill d-flex flex-column justify-content-center">
         <div class="container-tight py-6">
            <div class="text-center mb-4">
               <img src="./static/logo.svg" height="36" alt="">
            </div>
            <form class="card card-md" action="<?=htmlentities($_SERVER['REQUEST_URI'])?>" method="post" id="login-form">
               <div class="card-body">
                  <h2 class="mb-4 text-center">User Login</h2>
                  <?php if (isset($err) && !empty($err)): ?>
                  <div class="alert alert-danger"><?=$err?></div>
                  <?php endif; ?>
                  <div class="mb-3">
                     <label class="form-label">Username</label>
                     <input type="text" class="form-control" name="username" placeholder="Enter username" autocomplete="off">
                  </div>
                  <div class="mb-2">
                     <label class="form-label">
                     Password
                     </label>
                     <div class="input-group input-group-flat">
                        <input type="password" name="password" class="form-control"  placeholder="Enter password" >
                        <span class="input-group-text">
                           <a href="#" class="link-secondary" title="Show password" data-toggle="tooltip">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"/>
                                 <circle cx="12" cy="12" r="2" />
                                 <path d="M2 12l1.5 2a11 11 0 0 0 17 0l1.5 -2" />
                                 <path d="M2 12l1.5 -2a11 11 0 0 1 17 0l1.5 2" />
                              </svg>
                           </a>
                        </span>
                     </div>
                  </div>
                  <div class="mb-2">
                     <label class="form-check">
                     <input type="checkbox" name="remember_me" class="form-check-input"/>
                     <span class="form-check-label">Remember me on this device</span>
                     </label>
                  </div>
                  <div class="form-footer">
                     <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
</html>
