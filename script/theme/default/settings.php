<?php defined("APP") or die() // Links Page ?>
<div class="content">
   <div class="container-xl">
      <div class="page-header">
         <div class="row align-items-center">
            <div class="col-auto">
               <h2 class="page-title">
                  Settings
               </h2>
            </div>
         </div>
      </div>
      <!-- Content here -->
      <div class="row">
         <div class="col-lg-9">
            <div class="card card-lg">
               <div class="card-body">
                  <form class="" action="<?=$_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">
                     <h3 class="card-title"> <u>General settings</u> </h3>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-check form-switch">
                           <input class="form-check-input" name="firewall" id="firewall" type="checkbox" <?php if($this->config['firewall'] == 1) echo 'checked'; ?>>
                           <span class="form-check-label">Hot link protection</span>
                           </label>
                        </div>
                        <div class="col-sm-8">
                           <label class="form-label">Allowed domains</label>
                           <textarea name="allowed_domains" id="allowed_domains"  class="form-control" rows="3" <?php if($this->config['firewall'] != 1) echo 'disabled="disabled"'; ?> placeholder="domain1.com, domain2.com" cols="80"><?php
                              $ad ='';
                                  if (!empty(trim($this->config['allowed_domains']))) {
                                    $ad = implode(', ', json_decode($this->config['allowed_domains'], true));
                                  }

                               ?><?=$ad?></textarea>
                           <small>*Each domain separate by comma.</small>
                        </div>
                     </div>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-label">Dark Theme (Admin panel) :</label>
                        </div>
                        <div class="col-sm-8">
                           <label class="ml-4 form-check form-switch">
                           <input class="form-check-input" type="checkbox" name="dark_theme" <?php if($this->config['dark_theme'] == 1) echo 'checked'; ?>>
                           </label>
                        </div>
                     </div>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-label">API Key :</label>
                        </div>
                        <div class="col-sm-8">
                           <input class="form-control" type="text" name="apikey" value="<?=$this->config['apikey']?>" >
                        </div>
                     </div>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-label">Subtitle Languages :</label>
                        </div>
                        <div class="col-sm-8">
                           <textarea name="sublist" class="form-control" rows="5" cols="80"><?php
                              $sublist ='';
                                  if (!empty(trim($this->config['sublist']))) {
                                    $sublist = implode(', ', json_decode($this->config['sublist'], true));
                                  }

                               ?><?=$sublist?></textarea>
                        </div>
                     </div>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-label">Timezone :</label>
                        </div>
                        <div class="col-sm-8">
                           <select class="form-select" name="timezone" >
                           <?php $tzlist = Main::getTimeZoneList();
                              foreach ($tzlist as $tz) {
                                $selected = ($this->config['timezone'] == $tz ) ? 'selected' : '';
                                echo "<option value='{$tz}' {$selected}>{$tz}</option>";
                              }
                                ?>
                           </select>
                        </div>
                     </div>
                     <h3 class="card-title"> <u>Player settings</u> </h3>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-label">Netflix Skin :</label>
                        </div>
                        <div class="col-sm-8">
                           <label class="ml-4 form-check form-switch">
                           <input class="form-check-input" type="checkbox" name="netflix_skin" <?php if($this->config['netflix_skin'] == 1) echo 'checked'; ?>>
                           </label>
                        </div>
                     </div>
                     <div class="form-group row mb-3">
                        <div class="col-sm-4">
                           <label class="form-label">Logo :</label>
                        </div>
                        <div class="col-sm-8">
                           <input  type="file" name="player_logo"  >
                           <?php if (!empty($this->config['player_logo'])): ?>
                           <img src="<?=PROOT?>/uploads/<?=$this->config['player_logo']?>" height="50" alt="logo">
                           <input type="text" hidden name="plogo" value="<?=$this->config['player_logo']?>">
                           <?php endif; ?>
                        </div>
                     </div>
                     <hr>
                     <div class="mt-5 text-right">
                        <button type="submit" class="btn btn-lg btn-primary">Save changes</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
