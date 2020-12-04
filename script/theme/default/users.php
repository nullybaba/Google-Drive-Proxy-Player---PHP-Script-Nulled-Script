<?php defined("APP") or die() // Main Page ?>
<div class="content">
<div class="container-xl">
   <!-- Page title -->
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col-auto">
            <h2 class="page-title">
               <?=ucwords($action)?> User
            </h2>
         </div>
      </div>
   </div>
   <!-- Content here -->
   <?php if (!$default): ?>
   <div class="row">
      <div class="col-lg-9">
         <div class="card card-lg">
            <div class="card-body">
               <div class="markdown markdown-form">
                  <div class="example-content">
                     <?php if (!empty($err)): ?>
                     <div class="alert alert-danger"><?=$err?></div>
                     <?php else: ?>
                     <form id="form-user" name="foo" onsubmit="return false"  autocomplete="off" class="<?php  if($isEdit) echo 'editUser'?>" >
                        <div class="mb-3" id="alert"> </div>
                        <div class="mb-3">
                           <label class="form-label">Username</label>
                           <input type="text" class="form-control" name="username" value="<?=$user['username']?>" placeholder="Enter Username" >
                        </div>
                        <div class="mb-3">
                           <label class="form-label">Email</label>
                           <input type="email" class="form-control" name="email" value="<?=$user['email']?>"  placeholder="Enter Email Address">
                        </div>
                        <div class="mb-3">
                           <label class="form-label"><?= (!$isEdit) ? 'Password' : 'New password' ?></label>
                           <div class="input-group input-group-flat">
                              <input type="password" name="password" placeholder="Enter Password" value="<?=(!$isEdit) ? $user['password'] : '' ?>" class="form-control">
                              <span class="input-group-text">
                              <a href="javascript:void(0)" class="input-group-link">Show password</a>
                              </span>
                           </div>
                        </div>
                        <?php
                           if ($this->config['adminId'] != $user['id']):
                           $pr = (!empty($user['permission'])) ? $user['permission'] : '{}';
                           $pr = json_decode($pr, true);

                            ?>
                        <div class="mb-3">
                           <label class="form-label">Permission For Manage Links</label>
                           <div class="form-selectgroup">
                              <label class="form-selectgroup-item">
                              <input type="checkbox" name="permission[]" value="1" class="form-selectgroup-input" <?php if(in_array(1, $pr) || !$isEdit) echo 'checked'; ?> >
                              <span class="form-selectgroup-label">Add</span>
                              </label>
                              <label class="form-selectgroup-item">
                              <input type="checkbox" name="permission[]" value="2" class="form-selectgroup-input" <?php if(in_array(2, $pr)) echo 'checked'; ?> >
                              <span class="form-selectgroup-label">Edit</span>
                              </label>
                              <label class="form-selectgroup-item">
                              <input type="checkbox" name="permission[]" value="3" class="form-selectgroup-input" <?php if(in_array(3, $pr)) echo 'checked';  ?> >
                              <span class="form-selectgroup-label">Delete</span>
                              </label>
                           </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($isEdit): ?>
                        <input type="text" name="id" value="<?=$user['id']?>" hidden>
                        <input type="text" name="isEdit" value="1" hidden>
                        <?php endif; ?>
                        <div class="mt-5 text-right">
                           <button class="btn btn-block btn-primary" id="save-user">Save User</button>
                        </div>
                     </form>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php else: ?>
   <div class="card">
      <div class="table-responsive">
         <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
               <tr>
                  <th class="w-1">ID.
                  </th>
                  <th>Username
                  </th>
                  <th>Email
                  </th>
                  <th>Role
                  </th>
                  <th class="text-center">Links
                  </th>
                  <th>
                     Created At.
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z"/>
                        <polyline points="7 8 3 12 7 16" />
                        <polyline points="17 8 21 12 17 16" />
                        <line x1="14" y1="4" x2="10" y2="20" />
                     </svg>
                  </th>
                  <th>Last Logged At
                  </th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               <?php foreach($users as $user): ?>
               <tr data-id="<?=$user['id']?>">
                  <td><span class="text-muted">#<?=$user['id']?></span></td>
                  <td>
                     <b> <i><?=$user['username']?></i> </b>
                  </td>
                  <td><a href="#" class="text-reset title" tabindex="-1" title="<?=$user['email']?>"><?=$user['email']?></a></td>
                  <td>
                     <span class="badge"><?=$user['role']?></span>
                  </td>
                  <td>
                     links
                  </td>
                  <td>
                     <?=Main::dateFormat($user['created_at'])?>
                  </td>
                  <td >
                     <?=Main::dateFormat($user['last_logged'])?>
                  </td>
                  <td class="text-right">
                     <div class="btn-list flex-nowrap">
                        <?php if ($this->isAdmin): ?>
                        <a href="<?=PROOT?>/users/edit/<?=$user['id']?>" class="btn btn-sm btn-secondary " title="edit user"  >
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6-6a6 6 0 0 1 -8 -8l3.5 3.5"></path>
                           </svg>
                        </a>
                        <?php endif; ?>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary " title="copy plyer link" >
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                              <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                           </svg>
                        </a>
                        <?php if ($this->isAdmin && $this->config['adminId'] != $user['id']  ): ?>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary delete-user " title="delete user" data-id="<?=$user['id']?>" >
                           <svg class="icon icon-md m-0" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                              <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
                           </svg>
                        </a>
                        <?php endif; ?>
                     </div>
                  </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
   <?php endif; ?>
</div>
