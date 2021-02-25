import imageUploader from './imageUploader'

let counter = 0

const appendItem = (item, currentData = null) => {
    let newItem = item.cloneNode(true);
    newItem.style.display = ''
    let inputs = newItem.querySelectorAll('input')
    let objectName = `new_${counter}`
    if (currentData) {
        objectName = currentData.id
    } else {
        counter++
    }

    for (const input of inputs) {
        input.disabled = false
        let name = input.getAttribute('attr-name')
        input.setAttribute('name', `product_options[${objectName}][${name}]`);
        if (currentData) {
            let value = currentData[name]
            if (['text', 'number'].includes(input.type)){
                input.value = value
            }

            if (input.type == 'checkbox'){
                input.checked = value
            }
            
            if (name == 'image'){
                input.closest('div').querySelector('img').src = value
            }
        }
    }
    let deleteButton = newItem.querySelector('button')
    deleteButton.addEventListener('click', function(e){
        let btn = e.target
        let selector = btn.getAttribute('data-target')
        btn.closest(selector).outerHTML = ''
    })
    container.appendChild(newItem)
    const product_option_image_previews = newItem.querySelectorAll('.product_option_preview_image')
    imageUploader(product_option_image_previews)
}

const initProductOptionsTable = (        
    add_button, 
    container, 
    item,
    product_options_json
) => {
    const product_options = JSON.parse(product_options_json)

    for (const product_option of product_options) {
        appendItem(item, product_option)
    }

    add_button.addEventListener('click', function(){
        appendItem(item)
    })
}

export default initProductOptionsTable
