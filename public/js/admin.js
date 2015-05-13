$(document).ready(function(){
	//改变书籍状态为2
	$(".icon-arrow-right&.publishing").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定将选中书籍改为已完成吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(1).text();
				var data = {"id":id,"type":"2"};
				$.ajax({
					url:'/admin/changebookstate',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//改变书籍状态为1
	$(".icon-arrow-left&.published").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定将选中书籍改为正在交易吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(1).text();
				var data = {"id":id,"type":"1"};
				$.ajax({
					url:'/admin/changebookstate',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//改变求书状态为2
	$(".icon-arrow-right&.seeking").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定将选中求书改为已完成吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id,"type":"2"};
				$.ajax({
					url:'/admin/changebuystate',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//改变求书状态为1
	$(".icon-arrow-left&.seeked").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定将选中求书改为正在求书吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id,"type":"1"};
				$.ajax({
					url:'/admin/changebuystate',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//改变用户状态为 2
	$(".icon-arrow-right&.user").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定改变该用户状态吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id,"state":"2"};
				$.ajax({
					url:'/admin/changeuserstate',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//改变用户状态为 1
	$(".icon-arrow-left&.user").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定改变该用户状态吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id,"state":"1"};
				$.ajax({
					url:'/admin/changeuserstate',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//删除卖书
	$(".icon-trash&.publish").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定删除此条书籍吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(1).text();
				var data = {"id":id};
				$.ajax({
					url:'/admin/delbook',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//删除求书
	$(".icon-trash&.seek").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定删除此求书吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id};
				$.ajax({
					url:'/admin/delbuy',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//删除留言
	$(".icon-trash&.mes").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定删除此条留言吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id};
				$.ajax({
					url:'/admin/delmes',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//删除评论
	$(".icon-trash&.reply").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定删除此条评论吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(5).text();
				var data = {"id":id};
				$.ajax({
					url:'/admin/delreply',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})

	//删除评论 用户
	$(".icon-trash&.replyuser").click(function(){
		if ($(".name").text()=="super") {
			if (confirm("确定删除此条评论吗？")) {
				var $this = $(this);
				var id = $(this).parents("td").siblings("td").eq(0).text();
				var data = {"id":id};
				$.ajax({
					url:'/admin/delreply',
					method:'post',
					data:data,
					success:function(data){
						if (data=="sucess") {
							$this.parents("tr").hide();
						}
					}
				})
			}
		} else {
			alert("Sorry，你没有操作权限！");
			return false;
		}
	})
})