$(function(){
    $('#search_button').on('click',function() {
        let keyword  = $('#keyword').val();
        let company = $('#company').val();
        let min_price = $('#min_price').val();
        let max_price = $('#max_price').val();
        let min_stock = $('#min_stock').val();
        let max_stock = $('#max_stock').val();
        $.ajax({
            url:'/step/public/search',
            type:'GET',
            contentType: "application/json",
            data: {
                keyword: keyword,
                company: company,
                min_price: min_price,
                max_price: max_price,
                min_stock: min_stock,
                max_stock: max_stock
            },
        }).done(function(json){
            $('#tbody').children().remove();
            for (let i = 0; i < json['products'].length; i++) {
                let tags;
                tags += "<tr>";
                tags += "<td class='product_id'>" + json['products'][i].id + "</td>";
                tags += "<td>";
                tags += "<img src='../../storage/" + json['products'][i].img_path + "' width='15%'>"; 
                tags += "</td>";
                tags += "<td>" + json['products'][i].product_name + "</td>";
                tags += "<td>" + json['products'][i].price + "</td>";
                tags += "<td>" + json['products'][i].stock + "</td>";
                tags += "<td>" + json['products'][i].company_name + "</td>";
                tags += "<td><a href='http://localhost:8888/step/public/show/" + json['products'][i].id + "' class='btn btn-primary'>詳細表示</a></td>"; 
                tags += "<td>";
                tags += "<button type='button' class='btn btn-danger destory-button'>削除</button>";
                tags += "</td>";
                tags += "</tr>";
                $('#tbody').append(tags);
            }
        }).fail(function(json){
            console.log('ajax失敗');  
        });
    });
});
$(function(){
    $(document).on('click', '.destory-button', function() {
        var product_id = $(this).parent().parent().find('.product_id').text();
        if (!confirm('削除してもいいですか？')) {
            return false;
        }
        // trタグを非表示
        $(this).parent().parent().hide();
        
    });
});

