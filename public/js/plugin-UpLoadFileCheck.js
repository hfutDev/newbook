UpLoadFileCheck = function() {
  this.AllowExt = ".jpg,.gif,.png,.jpeg"; //允许上传的文件类型 0为无限制 每个扩展名后边要加一个"," 小写字母表示 
  this.ImgObj = new Image();
  this.FileExt = "";
  this.ErrMsg = "";
}

UpLoadFileCheck.prototype.CheckExt = function(obj) {
  this.ErrMsg = "";
  this.ImgObj.src = obj.value;
  if (obj.value == "") {
    this.ErrMsg = "\n请选择一个文件";
  } else {
    this.FileExt = obj.value.substr(obj.value.lastIndexOf(".")).toLowerCase();
    if (this.AllowExt != 0 && this.AllowExt.indexOf(this.FileExt) == -1) //判断文件类型是否允许上传 
    {
      this.ErrMsg = "\n该文件类型不允许上传.请上传 " + this.AllowExt + " 类型的文件，当前文件类型为" + this.FileExt;
    }
  }
  if (this.ErrMsg != "") {
     $('.update-file').removeClass('succ-update').closest('p').find("i").html(this.ErrMsg);
    return false;
  } else{
      $('.update-file').addClass('succ-update').closest('p').find("i").html('<img src="/images/right-icon.png" align="absmiddle">');
  }
    // return this.CheckProperty(obj);
}
function updateFileCheck(obj){
  // alert('LL')
  var d = new UpLoadFileCheck();
    d.CheckExt(obj)
}