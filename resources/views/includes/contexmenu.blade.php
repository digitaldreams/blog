<div id="customContextMenu" class="bg-light"
     style="width: 100%;z-index: 999;">
    <div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <a class="nav-item nav-link active" id="nav-bootstrapClassList-tab" data-toggle="tab"
                   href="#nav-bootstrapClassList" role="tab"
                   aria-controls="nav-bootstrapClassList" aria-selected="true">Classes</a>

                <a class="nav-item nav-link hide" id="nav-input-tab" data-toggle="tab" href="#nav-inputTab" role="tab"
                   aria-controls="nav-inputTab" aria-selected="false">Input</a>


            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-bootstrapClassList" role="tabpanel"
                 aria-labelledby="nav-bootstrapClassList-tab">

                <div class="border border-bottom border-light p-2 text" style="font-size: 10px">
                    <label class="checkbox-inline">
                        <input type="radio" name="currentlySelectedNode" checked value="self"
                               id="currentlySelectedNodeOption">
                        <span id="currentlySelectedNode">Self</span>
                    </label>

                    <label class="checkbox-inline">
                        <input type="radio" name="currentlySelectedNode" id="currentlySelectedNodeParentOption"
                               value="parent">
                        <span id="currentlySelectedNodeParent">Parent</span>
                    </label>
                    <label class="checkbox-inline">
                        <input type="radio" name="currentlySelectedNode" id="currentlySelectedNodeGrandParentOption"
                               value="grandParent">
                        <span id="currentlySelectedNodeGrandParent">Grand parent</span>
                    </label>
                    <div class="d-inline">
                        <button type="button" data-toggle="tooltip" title="Remove currently selected element"
                                class="btn btn-link btn-xs text-danger" id="removeCurrentlySelectedNode"><i
                                    class="fa fa-remove"></i>
                        </button>
                    </div>
                    <div class="d-inline">
                        <button data-toggle="tooltip" title="Copy currently selected element to its right" type="button"
                                class="btn btn-link btn-xs text-success" id="cloneCurrenltySelectedNode"><i
                                    class="fa fa-copy"></i></button>
                    </div>
                </div>
                <label for="addClassesToNode">Class List</label>
                <select class="form-control" style="width:100%" multiple id="addClassesToNode"></select>
                <hr class="divider"/>
                <div class="form-group text-right">
                    <input type="submit" id="addBootStrapClassBtn" class="btn btn-outline-primary" value="Save">
                </div>
            </div>


            <div class="tab-pane fade" id="nav-inputTab" role="tabpanel" aria-labelledby="nav-input-tab">

                <div class="form-group form-group-sm">
                    <label for="inputPlaceholder">Placeholder</label>
                    <input type="text" name="placeholder" id="inputPlaceholder"
                           class="form-control text-muted form-control-sm"
                           placeholder="placeholder text">
                </div>

                <div class="form-group form-group-sm">
                    <label for="inputTagName">Name</label>
                    <input type="text" name="inputTagName" id="inputTagName"
                           class="form-control text-muted form-control-sm"
                           placeholder="name">
                </div>

                <div class="form-row">
                    <div class="col checkbox-inline">
                        <label>
                            <input type="checkbox" name="isReadOnly" value="1" id="inputIsReadOnly">
                            Read Only
                        </label>
                    </div>
                    <div class="col checkbox-inline">
                        <label>
                            <input type="checkbox" name="isRequired" value="1" id="inputIsRequired">
                            Required
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col form-group">
                        <label for="inputMinAttr">Min</label>
                        <input type="text" name="inputMinAttr" id="inputMinAttr" placeholder="e.g. 1"
                               class="form-control form-control-sm">
                    </div>
                    <div class="col form-group">
                        <label for="inputMaxAttr">Max</label>
                        <input type="text" name="inputMaxAttr" id="inputMaxAttr" placeholder="e.g. 10"
                               class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id="insertInputTabSaveBtn">Save</button>
                </div>
            </div>

        </div>
    </div>
    <hr class="border border-secondary"/>
    <div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">


                <a class="nav-item nav-link" id="nav-fakeTable-tab" data-toggle="tab" href="#nav-fakeTable" role="tab"
                   aria-controls="nav-profile" aria-selected="false">Table</a>

                <a class="nav-item nav-link" id="nav-fakeTabs-tab" data-toggle="tab" href="#nav-fakeTabs" role="tab"
                   aria-controls="nav-profile" aria-selected="false">Tab</a>


                <a class="nav-item nav-link" id="nav-chartjs-tab" data-toggle="tab" href="#nav-chartjs" role="tab"
                   aria-controls="nav-chartjs" aria-selected="false">chart</a>

                <a class="nav-item nav-link" id="nav-voice-tab" data-toggle="tab" href="#nav-voice" role="tab"
                   aria-controls="nav-chartjs" aria-selected="false">Voice</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent2">

            <div class="tab-pane fade" id="nav-fakeTable" role="tabpanel" aria-labelledby="nav-fakeTable-tab">

                <div class="form-group">
                    <label for="insertTableFields">Fields</label>
                    <select id="insertTableFields" class="form-control" multiple style="width: 100%"></select>
                </div>

                <div class="form-group">
                    <label for="totalTableRow">Total</label>
                    <input type="number" min="5" max="200" value="5" class="form-control" id="totalTableRow">
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id="insertTableModalSaveBtn">Save</button>
                </div>

            </div>

            <div class="tab-pane fade" id="nav-fakeTabs" role="tabpanel" aria-labelledby="nav-fakeTabs-tab">

                <div class="form-group">
                    <label for="insertTabsFields">Tabs</label>

                    <select id="insertTabsFields" class="form-control" multiple style="width: 100%">
                        <option>Home</option>
                        <option>Profile</option>
                        <option>Activity</option>
                        <option>Password</option>
                        <option>Work</option>
                        <option>Posts</option>
                        <option>Favourite</option>
                    </select>

                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id="insertTabsModalSaveBtn">Save</button>
                </div>
            </div>

            <div class="tab-pane fade" id="nav-chartjs" role="tabpanel" aria-labelledby="nav-chartjs-tab">

                <div class="form-row">
                    <div class="form-group col">
                        <label>Chart Type</label>
                        <select id="insertChartjsType" class="form-control form-control-sm" style="width: 100%">
                            <option>line</option>
                            <option>bar</option>
                            <option>pie</option>
                            <option>doughnut</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="insertChartjsDataTypeFields">Data Type</label>

                        <select id="insertChartjsDataTypeFields" class="form-control form-control-sm"
                                style="width: 100%" multiple>
                            <option>Product A</option>
                            <option>Product B</option>
                            <option>Product C</option>

                        </select>

                    </div>

                </div>
                <div class="form-row">

                    <div class="form-group col">
                        <label>Time Range</label>
                        <select id="insertChartjsLablesFields" class="form-control form-control-sm" style="width: 100%">
                            <option value="">None</option>
                            <option value="day">Day</option>
                            <option value="week">week</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" id="insertChartjsTitle" class="form-control form-control-sm"
                           placeholder="e.g. Product sales report by month">
                </div>


                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary" id="insertChartjsSaveBtn">Save</button>
                </div>
            </div>

            <div class="tab-pane fade" id="nav-voice" role="tabpanel" aria-labelledby="nav-voice-tab">
                <div class="row">
                    <div class="col-sm-8">
                        <span id="voiceCommandMessage">Click mic to start</span>
                    </div>
                    <div class="col-sm-4">
                        <button class="text-gray btn" id="initVoiceRecognitionCommand"><i
                                    class="fa fa-microphone"></i>
                        </button>
                    </div>
                </div>
                <br/>

                <div class="form-group form-group-sm">

                    <div class="input-group input-group-sm">
                        <label class="input-group-addon" for="voiceCommandLanguage">Language</label>
                        <select id="voiceCommandLanguage" class="p-0 m-0 form-control">
                            <option value="bn-BD">Bangla</option>
                            <option value="en-IN">English (India)</option>
                            <option value="en-US">English (USA)</option>
                            <option value="en-UK">English (UK)</option>
                        </select>
                        <div class="input-group-addon p-0 m-0">
                            <label class="radio-inline">
                                <input type="checkbox" name="voiceCommandControl" id="voiceCommandControl"/>
                                Command
                            </label>
                        </div>
                    </div>
                </div>
                <hr/>
            </div>


        </div>
    </div>

</div>
