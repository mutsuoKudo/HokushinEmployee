import { type } from "os";

// 社員名簿削除アラート
function delete_alert(e) {

    if (!window.confirm('本当に削除しますか？')) {
        window.alert('キャンセルされました');
        return false;
    }
    document.deleteform.submit();
};


$(function() {
    $.ajax({
            type: 'get',
            datatype: 'json',
            url: '/'
        })
        .done(function(data) { //ajaxの通信に成功した場合
            alert("success!");
            console.log(data['all_avg']);
            $("#example").html(data['all_avg']);
        })
        .fail(function(data) { //ajaxの通信に失敗した場合
            alert("error!");
        });
});