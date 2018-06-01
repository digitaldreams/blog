<div class="file-tree">
    <h5 class=""><i class="fa fa-folder-open"></i>{{request()->route('project')->name}}
        <small>
            <button class="btn btn-outline-secondary" title="Create a root Folder" data-toggle="modal" data-target="#manageFolderModal" data-path="">&nbsp;<i
                        class="fa fa-plus-circle"></i> &nbsp;
            </button>
            @if(request()->route('project')->type==\Prototype\Models\Project::TYPE_THEME)
                <a class="btn btn-link" href="{{route('prototype::themes.plugin',request()->route('project')->key)}}">
                    <i class="fa fa-refresh"></i> Sync Plugin
                </a>
            @else
            <a class="btn btn-outline-secondary" title="download project files " href="{{route('prototype::projects.download',request()->route('project')->key)}}">
                &nbsp;<i class="fa fa-download"></i>&nbsp;
            </a>
            @endif
        </small>
    </h5>
    <hr/>
    <?php
    $directoryTree = new \Prototype\Services\DirectoryTree(request()->route('project')->path, request('path'));
    echo $directoryTree->run();
    ?>
    <hr/>
    <br/>

</div>