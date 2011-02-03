<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/save.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> Database Management</h2>
<p id="page-intro">Local Migration Manager</p>
<div class="clear"></div>
<div class="content-box">
    <div class="content-box-header">
        <h3>Schemas</h3>
        <ul class="content-box-tabs">
            <li><a href="#orm" class="default-tab">ORM YML</a></li>
            <li><a href="#schemas">Schemas</a></li>
            <li><a href="#migrations">Migrations</a></li>
            <li><a href="#models">Models</a></li>
            <li><a href="#fixtures">Fixtures</a></li>
            <li><a href="#help">Help</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
    <!--// ORM DESIGNER //-->
        <div class="tab-content default-tab" id="orm">
        <?PHP if (count($dev_schema_split_files) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="100">File</th>
                        <th width="275">Modified</th>
                        <th width="50">Lines</th>
                        <th>Size</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="bulk-actions align-left">
                                <a class="button" href="<?PHP echo base_url(); ?>system/database/migration/orm/designer/merge/files" onclick="return confirm('Are you sure you want to Merge the ORM YML Files?\nThis will create a new Schema File.');">Merge Current ORM YML Files into New Schema</a>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                <?PHP foreach ($dev_schema_split_files as $file): ?>
                    <tr>
                        <td nowrap="nowrap"><a href="<?PHP echo base_url(); ?>system/database/migration/view/orm/file/<?PHP echo substr($file['name'], 0, -4); ?>" title="<?PHP echo $file['full_path']; ?>"><?PHP echo $file['name']; ?></a></td>
                        <td nowrap="nowrap"><?PHP echo $file['modified']; ?></td>
                        <td nowrap="nowrap"><?PHP echo $file['lines']; ?></td>
                        <td nowrap="nowrap"><?PHP echo $file['size']; ?></td>
                    </tr>
                <?PHP endforeach; ?>
                </tbody>
            </table>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>There are currently no files to display.</div>
            </div>
        <?PHP endif; ?>
        </div>
    <!--// SCHEMAS //-->
        <div class="tab-content" id="schemas">
        <?PHP if (count($dev_schema_merge_files) > 0): ?>
            <div class="notification png_bg" style="display: none;" id="schemas_notice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'schema_files',
                'id' => 'schema_files',
                'onsubmit' => 'return schemas_validate();'
            );
            echo form_open(base_url().'system/database/migration/delete/schemas', $attributes);
            ?>
                <table>
                    <thead>
                        <tr>
                            <th width="25"><input class="check-all" type="checkbox" /></th>
                            <th width="100">File</th>
                            <th width="275">Modified</th>
                            <th width="50">Lines</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <div class="bulk-actions align-left">
                                    <select name="schema_action" id="schema_action">
                                        <option value="" selected="selected">Choose an action...</option>
                                        <option value="diff">Create Migration Diff</option>
                                        <option value="delete" style="color: #990000">Delete</option>
                                    </select>
                                    <input type="submit" class="button" value="Apply to selected" />
                                    <a class="button" href="<?PHP echo base_url(); ?>system/database/migration/copy/schemas" onclick="return confirm('This will overwrite the local development schemas. Continue?');">Copy Deployed Schema History</a>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?PHP foreach ($dev_schema_merge_files as $file): ?>
                        <tr>
                            <td><input type="checkbox" value="<?PHP echo $file['name']; ?>" name="schema_files[]" /></td>
                            <td nowrap="nowrap"><a href="<?PHP echo base_url(); ?>system/database/migration/view/schema/file/<?PHP echo substr($file['name'], 0, -4); ?>" title="<?PHP echo $file['full_path']; ?>"><?PHP echo $file['name']; ?></a></td>
                            <td nowrap="nowrap"><?PHP echo $file['modified']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['lines']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['size']; ?></td>
                        </tr>
                    <?PHP endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <!--//<div>There are currently no files to display. To generate a Schema file you will need to merge the current <a href="#" onclick="$('a[href=\'#orm\']').click();"><strong>ORM YML</strong></a> files.</div>//-->
                <div>There are currently no files to display. Click the <strong>Copy Live Schema History</strong> button below.</div>
            </div>
            <input type="button" class="button" value="Copy Deployed Schema History" onclick="window.location='<?PHP echo base_url(); ?>system/database/migration/copy/schemas';" />
        <?PHP endif; ?>
        </div>
    <!--// MIGRATIONS //-->
        <div class="tab-content" id="migrations">
        <?PHP if (count($dev_migrations_files) > 0): ?>
            <div class="notification png_bg" style="display: none;" id="migrations_notice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'migration_files',
                'id' => 'migration_files',
                'onsubmit' => 'return migration_validate();'
            );
            echo form_open(base_url().'system/database/migration/delete/migrations', $attributes);
            ?>
                <table>
                    <thead>
                        <tr>
                            <th width="25"><input class="check-all" type="checkbox" /></th>
                            <th width="225">File</th>
                            <th width="275">Modified</th>
                            <th width="50">Lines</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <div class="bulk-actions align-left">
                                    <select name="migration_action" id="migration_action">
                                        <option value="" selected="selected">Choose an action...</option>
                                        <option value="delete" style="color: #990000">Delete</option>
                                    </select>
                                    <input type="submit" class="button" value="Apply to selected" />
                                    <a class="button" href="<?PHP echo base_url(); ?>system/database/migration/copy/migrations" onclick="return confirm('This will overwrite the local development migrations. Continue?');">Copy Deployed Migration History</a>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?PHP foreach ($dev_migrations_files as $file): ?>
                        <tr>
                            <td><input type="checkbox" value="<?PHP echo $file['name']; ?>" name="migration_files[]" /></td>
                            <td nowrap="nowrap"><a href="<?PHP echo base_url(); ?>system/database/migration/view/migration/file/<?PHP echo substr($file['name'], 0, -4); ?>" title="<?PHP echo $file['full_path']; ?>"><?PHP echo $file['name']; ?></a></td>
                            <td nowrap="nowrap"><?PHP echo $file['modified']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['lines']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['size']; ?></td>
                        </tr>
                    <?PHP endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>There are currently no files to display.</div>
            </div>
            <input type="button" class="button" value="Copy Deployed Migration History" onclick="window.location='<?PHP echo base_url(); ?>system/database/migration/copy/migrations';" />
        <?PHP endif; ?>
        </div>
    <!--// MODELS //-->
        <div class="tab-content" id="models">
            <div class="notification png_bg" style="display: none;" id="models_notice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
        <?PHP if (count($dev_models_files) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="225">File</th>
                        <th width="275">Modified</th>
                        <th width="50">Lines</th>
                        <th>Size</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="bulk-actions align-left">
                                <select name="models_version" id="models_version" class="selectlist">
                                    <option value="" selected="selected">Choose Schema ...</option>
                                <?PHP foreach ($dev_schema_merge_files as $file): ?>
                                    <option value="<?PHP echo str_replace('schema', '', substr($file['name'], 0, -4)); ?>"><?PHP echo str_replace('schema', 'Schema Version ', substr($file['name'], 0, -4)); ?></option>
                                <?PHP endforeach; ?>
                                </select>
                                <a class="button" href="#" onclick="generate_models(); return false;">Update Models</a>
                            </div>
                            <div class="clear"></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                <?PHP foreach ($dev_models_files as $file): ?>
                    <tr>
                        <td nowrap="nowrap"><img src="<?PHP echo $media_url; ?>system/images/icons/16x16/<?PHP echo $dev_models_changes[$file['name']]['icon']; ?>.png" title="<?PHP echo $dev_models_changes[$file['name']]['title']; ?>" style="vertical-align: middle; cursor: help;" alt="" />&nbsp; <a href="<?PHP echo base_url(); ?>system/database/migration/view/model/file/<?PHP echo substr($file['name'], 0, -4); ?>" title="<?PHP echo $file['full_path']; ?>"><?PHP echo $file['name']; ?></a></td>
                        <td nowrap="nowrap"><?PHP echo $file['modified']; ?></td>
                        <td nowrap="nowrap"><?PHP echo $file['lines']; ?></td>
                        <td nowrap="nowrap"><?PHP echo $file['size']; ?></td>
                    </tr>
                <?PHP endforeach; ?>
                </tbody>
            </table>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>There are currently no files to display.</div>
            </div>
            <?PHP if (count($dev_schema_merge_files) == 0): ?>
            <div class="notification error png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>You will not be able to generate Doctrine Models until there is at least one <a href="#" onclick="$('a[href=\'#schemas\']').click();"><strong>Schema file</strong></a>.</div>
            </div>
            <?PHP else: ?>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <select name="models_version" id="models_version" class="selectlist">
                                <option value="" selected="selected">Choose Schema ...</option>
                            <?PHP foreach ($dev_schema_merge_files as $file): ?>
                                <option value="<?PHP echo str_replace('schema', '', substr($file['name'], 0, -4)); ?>"><?PHP echo str_replace('schema', 'Schema Version ', substr($file['name'], 0, -4)); ?></option>
                            <?PHP endforeach; ?>
                            </select>
                            <a class="button" href="#" onclick="generate_models(); return false;">Create Models</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?PHP endif; ?>
        <?PHP endif; ?>
        </div>
    <!--// FIXTURES //-->
        <div class="tab-content" id="fixtures">
        <?PHP if (count($dev_fixtures_files) > 0): ?>
            <div class="notification png_bg" style="display: none;" id="fixtures_notice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'fixtures_files',
                'id' => 'fixtures_files',
                'onsubmit' => 'return fixtures_validate();'
            );
            echo form_open(base_url().'system/database/migration/delete/fixtures', $attributes);
            ?>
                <table>
                    <thead>
                        <tr>
                            <th width="25"><input class="check-all" type="checkbox" /></th>
                            <th width="100">File</th>
                            <th width="300">Modified</th>
                            <th width="100">Lines</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <div class="bulk-actions align-left">
                                    <select name="fixtures_action" id="fixtures_action">
                                        <option value="" selected="selected">Choose an action...</option>
                                        <option value="delete" style="color: #990000">Delete</option>
                                    </select>
                                    <a class="button" href="#" onclick="fixtures_validate(); return false;">Apply to selected</a>
                                    <a class="button" href="<?PHP echo base_url(); ?>system/database/migration/generate/fixtures" onclick="return confirm('Are you sure you want to regenerate fixtures from the database?\n\nThis will trash your current Development Fixtures!!!\n\nThis may take some time, depending on how large your database is...');">Regenerate Fixtures from Database</a>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?PHP foreach ($dev_fixtures_files as $file): ?>
                        <tr>
                            <td><input type="checkbox" value="<?PHP echo $file['name']; ?>" name="files[]" /></td>
                            <td nowrap="nowrap"><a href="<?PHP echo base_url(); ?>system/database/migration/view/fixture/file/<?PHP echo substr($file['name'], 0, -4); ?>" title="<?PHP echo $file['full_path']; ?>"><?PHP echo $file['name']; ?></a></td>
                            <td nowrap="nowrap"><?PHP echo $file['modified']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['lines']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['size']; ?></td>
                        </tr>
                    <?PHP endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>There are currently no files to display.</div>
            </div>
            <p><a class="button" href="<?PHP echo base_url(); ?>system/database/migration/generate/fixtures" onclick="return confirm('Are you sure you want to generate fixtures from the database?\nThis may take some time, depending on how large your database is...');">Generate Fixtures from Database</a></p>
        <?PHP endif; ?>
        </div>
    <!--// HELP //-->
        <div class="tab-content" id="help">
            <h3>Overview</h3>
            <hr style="color: #EEE; border: 1px solid #EEE;" />
            <p>If you are coming to this section for the first time, you will need to complete a few tasks in order to get up and running.  Since we are using <a href="http://www.orm-designer.com/download-orm-designer" target="_blank">ORM Designer</a> to design the back-end, you will need to merge the ORM Designer files into a single Schema that we can use for Doctrine.  To do this, just click the <strong>ORM YML</strong> tab on this page and click the "<strong>Merge Current ORM YML Files into New Schema</strong>" button.  This will merge all the files on that page into a single Schema file that will be automatically versioned and placed into your local developer folder.  Once you have made a Schema file, you can generate Doctrine models from it by visiting the <strong>Models</strong> tab on this page.</p>
            <br /><br />

            <h3>ORM YML</h3>
            <hr style="color: #EEE; border: 1px solid #EEE;" />
            <p>This section can be accessed from the <strong>ORL YML</strong> tab on this page.  It provides a list of files that are generated from performing an export in the <a href="http://www.orm-designer.com/download-orm-designer" target="_blank">ORM Designer</a> software.  These files are automatically exported to the <strong>/application/doctrine/orm_designer/yml/</strong> directory. To view a file, you can click the file name.  At any time you can click the "<strong>Merge Current ORM YML Files into New Schema</strong>" button to generate a new schema file which can be access by the <strong>Schemas</strong> tab above.  However, please note that this system will only successfully create a new Schema file if there were differences found between the last Schema file and the one you were trying to create.  This prevents accidently creating a new Schema version that is identical to the previous version. You can access the <strong>Doctrine.ormdesigner</strong> file in the <strong>/application/doctrine/orm_designer/</strong> directory. You will need to have the latest version of <a href="http://www.orm-designer.com/download-orm-designer" target="_blank">ORM Designer</a> installed to open this file.</p>
            <br /><br />

            <h3>Schemas</h3>
            <hr style="color: #EEE; border: 1px solid #EEE;" />
            <p>Schema files are the core that is used for pretty much everything Doctrine can do.  If you have not already created a Schema file, or you need to update the Schema file to the latest version created by the <a href="http://www.orm-designer.com/download-orm-designer" target="_blank">ORM Designer</a> software, just visit the <strong>ORM YML</strong> tab and click the "<strong>Merge Current ORM YML Files into New Schema</strong>" button to generate a new schema file. Once you have a Schema file you can generate Doctrine models automatically by visiting the <strong>Models</strong> tab on this page. You can view a Schema at anytime by visiting the <strong>Schemas</strong> tab on this page and clicking a file name.  Once you have a schema file that is ready for production, you can manually copy it to the <strong>/application/doctrine/schemas/</strong> directory.  This is where the <strong>"Copy Deployed Schema History"</strong> button is getting its files.</p>
            <br /><br />

            <h3>Migrations</h3>
            <hr style="color: #EEE; border: 1px solid #EEE;" />
            <p>The purpose of the Migrations section is to make it easy to update the database schema from one version to another.  When you are on the <strong>Schemas</strong> tab, and have more than one schema file, you can select two files to automatically detect what needs to be done to get from the current version to the latest version.  First, select the current schema version you are using.  Then, select the new schema file you just created.  From the <strong>"Choose an action..."</strong> drop down list, choose <strong>"Create Migration Diff"</strong> and click the <strong>"Apply to selected"</strong> button.  This will create a new migration file that can be accessed from the <strong>Migrations</strong> tab.  Once you have a migration file that is ready for production, you can manually copy it to the <strong>/application/doctrine/migrations/</strong> directory.  This is where the <strong>"Copy Deployed Migration History"</strong> button is getting its files.  Once you have placed the migration file in the production folder it will be checked when you are accessing the <strong>"Update Database"</strong> page located <strong>/system/database/update</strong>.</p>
            <br /><br />

            <h3>Models</h3>
            <hr style="color: #EEE; border: 1px solid #EEE;" />
            <p>Models are the PHP Doctrine source code files that are created automatically from the schema file you selected. To generate updated PHP Doctrine Models access the <strong>Models</strong> tab and select the latest schema file from the <strong>"Choose Schema..."</strong> drop down list.  Once you have selected the file you want, click the <strong>"Create Models"</strong> button. This will generate your new models in the <strong>/application/doctrine/dev_local/models/</strong> directory. Once you have reviewed these new models and assured they are ready for production, you can manually copy/overwrite them to the <strong>/application/models/doctrine/</strong> directory. You may wish to remove some old files in the <strong>/application/models/doctrine/</strong> directory during this process, but please keep in mind that some of these files are not created by this automated process and therefore should not be deleted from this production directory.<br />
            <br />
            <strong style="color: #990000;">DO NOT MANUALLY DELETE THE FOLLOWING FILES:</strong><br />
            <span style="color: #990000;">they are required files that are not recreated by this system</span>
            <ul style="list-style: square; margin-left: 20px;">
                <li style="padding-left: 0px;">/application/models/doctrine/AutoExpire.php</li>
                <li style="padding-left: 0px;">/application/models/doctrine/AutoExpireListener.php</li>
                <li style="padding-left: 0px;">/application/models/doctrine/Taggable.php</li>
                <li style="padding-left: 0px;">/application/models/doctrine/TagGenerator.php</li>
                <li style="padding-left: 0px;">/application/models/doctrine/TagTable.php</li>
            </ul>
            </p>
            <br />

            <h3>Fixtures</h3>
            <hr style="color: #EEE; border: 1px solid #EEE;" />
            <p>Fixtures are used by Doctrine to pre-populate the database with useful data during a system installation or upgrade.  As you work with the database and begin inputting data, you can use the Fixtures section to automatically export the entire database as Fixtures that can be imported.  To generate fixtures at any time, just visit the <strong>Fixtures</strong> tab on this page and click the "<strong>Generate Fixtures from Database</strong>" button.  This will trash all previous Fixtures and recreate new ones directly from the database.  You can also view any Fixture by clicking its file name. More than likely, once you have created the fixtures file, you will need to manually edit it with you favorite text editor to remove anything you do not want to be apart of a system installation or upgrade. Once you have a fixtures file that is ready for production, you can manually copy it to the <strong>/application/doctrine/fixtures/</strong> directory</p>
            <br />

        </div>
    </div>
</div>
<script type="text/javascript">
function fixtures_validate()
{
    notification('#fixtures_notice', 'close');

    if($('#fixtures_action').val() == '')
    {
        notification('#fixtures_notice', 'open', 'Please choose an action.', 'attention');
        $('#fixtures_action').focus();
        return false;
    }
    else if( !$('#fixtures_files input:checked[name="files[]"]').val())
    {
        notification('#fixtures_notice', 'open', 'Please select at least one file to delete.', 'attention');
        $('#fixtures_action').focus();
        return false;
    }
    else
    {
        if(confirm('This will trash the selected Development Fixtures!!!'))
        {
            $('#fixtures_files').submit();
            return true;
        }
        else
        {
            return false;
        }
    }
}
function generate_models()
{
    notification('#models_notice', 'close');

    if($('#models_version').val() == '')
    {
        notification('#models_notice', 'open', 'Please select a Schema Version.', 'attention');
        $('#models_version').focus();
    }
    else if(confirm('Are you sure you want to create models from this Schema File?\n\nThis will trash your current Development Models!!!'))
    {
        window.location = '<?PHP echo base_url(); ?>system/database/migration/generate/models/version/'+$('#models_version').val();
    }
}
function schemas_validate()
{
    notification('#schemas_notice', 'close');

    if($('#schema_action').val() == '')
    {
        notification('#schemas_notice', 'open', 'Please choose an action.', 'attention');
        $('#schemas_action').focus();
        return false;
    }
    else if( !$('#schema_files input:checked[name="schema_files[]"]').val())
    {
        notification('#schemas_notice', 'open', 'Please select at least one file.', 'attention');
        $('#schemas_action').focus();
        return false;
    }
    else
    {
        if($('#schema_action').val() == 'delete' && confirm('This will trash the selected Development Schemas!!!'))
        {
            return true;
        }
        else if($('#schema_action').val() == 'diff')
        {
            if($('#schema_files input:checked[name="schema_files[]"]').length < 2)
            {
                notification('#schemas_notice', 'open', 'Please check two files to create a migration diff.', 'attention');
                $('#schemas_action').focus();
                return false;
            }
            else if($('#schema_files input:checked[name="schema_files[]"]').length > 2)
            {
                notification('#schemas_notice', 'open', 'Only two files can be checked to create a migration diff.', 'attention');
                $('#schemas_action').focus();
                return false;
            }
            else
            {
                var version = new Array();
                var key = 0;
                $('#schema_files input:checked[name="schema_files[]"]').each(function(){
                    version[key] = $(this).val().substring(6, $(this).val().lastIndexOf('.'));
                    key++;
                });

                version.sort();

                var from = parseFloat(version[0]);
                var to = parseFloat(version[1]);

                // make sure there is only one version difference between files
                if((to - from) != 1)
                {
                    notification('#schemas_notice', 'open', 'A migration diff should only be created from files one version apart.', 'attention');
                    $('#schemas_action').focus();
                    return false;
                }
                // check if for some reason from is greater than to, and swap them
                if(from > to)
                {
                    var temp_to = to;
                    var temp_from = from;
                    from = temp_to;
                    to = temp_from;
                }

                window.location = '<?PHP echo base_url(); ?>/system/database/migration/generate/migration/from/'+from+'/to/'+to;

                return false;
            }

            return false;
        }
        else
        {
            return false;
        }
    }
}
function migration_validate()
{
    notification('#migrations_notice', 'close');

    if($('#migration_action').val() == '')
    {
        notification('#migrations_notice', 'open', 'Please choose an action.', 'attention');
        $('#migration_action').focus();
        return false;
    }
    else if( !$('#migration_files input:checked[name="migration_files[]"]').val())
    {
        notification('#migrations_notice', 'open', 'Please select at least one file.', 'attention');
        $('#migration_action').focus();
        return false;
    }
    else if($('#migration_action').val() == 'delete' && confirm('This will trash the selected Development Migration Files!!!'))
    {
        return true;
    }
    else
    {
        return false;
    }
}
</script>