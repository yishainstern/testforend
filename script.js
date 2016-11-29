function dodo(){
	var name = $('input[name="name"]').val();
	var git = $('input[name="git"]').val();
	var ver = $('input[name="ver"]').val();
	var data = new FormData();
	data.append('name',name);
	data.append('git',git);
	data.append('ver',ver);
	$.ajax({
        url: "http://local.test/index.php",
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        complete: function (results) {
            

        }
    });
}


