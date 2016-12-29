

function writeToElemnt(elm, data) {
	$(elm).html(data);
}

function checkIfNewFolder(data) {
	switch (data.status) {
		case 0:
			writeToElemnt('#tell-if-new-folder',data.message);
			$('input[name="git"]').removeAttr('disabled');
			$('input[name="gitClone"]').removeAttr('disabled');
			break;
		case 1:
			writeToElemnt('#tell-if-new-folder',data.message);
			break;
		case 2:
			writeToElemnt('#tell-if-new-folder',data.message);
	}
	// body...
}


function ajaxfunc(urlForAjax,func,task){
	var form = document.forms.namedItem("form");
	var data = new FormData(form);
	data.append('task',task);
	$.ajax({
        url: urlForAjax,
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        complete: function (results) {
            func(JSON.parse(results.responseText));
        }
    });
}

function checkclone(data){
	console.log(data);
}

function checkIfcl(data){
	switch (data.status){
		case 0:
			writeToElemnt('#tell-if-did-clone',data.message);
			$('input[name="csvFile"]').removeAttr('disabled');
			$('input[name="get-ready"]').removeAttr('disabled');
			break;
		case 1:
			writeToElemnt('#tell-if-did-clone',data.message);
			break;
		case 2:
			writeToElemnt('#tell-if-new-folder',data.message);		
	}
}


function dodo(){
	ajaxfunc("http://local.test/index.php",checkIfNewFolder,'open folder');
}

function gogit(){
	ajaxfunc("http://local.test/index.php",checkIfcl,'clone git');
}

function checkClone(){
	ajaxfunc("http://local.test/index.php",checkclone,'check git');	
}

function csvf() {
	ajaxfunc("http://local.test/index.php",checkclone,'add version');
}

function ff() {
	ajaxfunc("http://local.test/index.php",checkclone,'run Python');
}

function startpom(){
	ajaxfunc("http://local.test/index.php",checkclone,'run Pom');
}

function autoFill(){
	$('input[name="id"]').val('sternyi');
	$('input[name="git"]').val('https://git-wip-us.apache.org/repos/asf/ant.git');
	$('input[name="gitName"]').val('ant');
	$('input[name="ver"]').val('ANT_170,ANT_170_B1,ANT_171');
	$('input[name="pomPath"]').val('src\\etc\\poms');
}