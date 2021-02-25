function initCartDeleteButton(actionUrl){
    var cartDeleteBtns = document.querySelectorAll('.cartDeleteBtn');
    for(var index = 0; index < cartDeleteBtns.length ; index ++){
        var cartDeleteBtn = cartDeleteBtns[index]
        cartDeleteBtn.addEventListener('click', function(e){
            var btn = e.target
            var dataId = btn.getAttribute('data-id');
            var formData = new FormData();
            formData.append("_method", 'DELETE');
            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]')
            var csrfToken = csrfTokenMeta.content
            formData.append("_token", csrfToken);
            formData.append("product_option_id", dataId);
            var request = new XMLHttpRequest();
            request.open("POST", actionUrl);
            request.onreadystatechange = function () {
                if(request.readyState === XMLHttpRequest.DONE 
                    && request.status === 200
                    && request.responseText === "success"
                    ) {
                    window.location.reload()
                }
            };
            request.send(formData);
        });
    }
}

export {initCartDeleteButton}
