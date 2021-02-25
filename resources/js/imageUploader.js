const imageUploader = (containers) => {
    for (const container of containers) {
        const input = container.querySelector('input[type=file]')
        input.addEventListener('change', function(e){
            readURL(e.target)
        })

        const img = container.querySelector('img')
        const oldSrc = img.getAttribute('src');
        function readURL(input){
            if(input.files && input.files[0]){
                const reader = new FileReader();
                reader.onload = function (e) {
                    img.setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                img.setAttribute('src', oldSrc);
            }
        }
    }
} 

export default imageUploader