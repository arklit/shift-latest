import Cropper from 'cropperjs';
import Modal from "bootstrap/js/dist/modal";
class CropperRocont extends window.Controller {

    static targets = [
        "source",
        "upload"
    ];

    /**
     *
     */
    connect() {
        console.log(1)
        let image = this.data.get('url') ? this.data.get('url') : this.data.get(`value`);

        if (image) {
            this.element.querySelector('.cropper-preview').src = image;
        } else {
            this.element.querySelector('.cropper-preview').classList.add('none');
            this.element.querySelector('.cropper-remove').classList.add('none');
        }

        let cropPanel = this.element.querySelector('.upload-panel');

        cropPanel.width = this.data.get('width');
        cropPanel.height = this.data.get('height');

        this.cropper = new Cropper(cropPanel, {
            viewMode: 2,
            aspectRatio: this.data.get('width') / this.data.get('height'),
            minContainerHeight: 500,
        });
    }

    /**
     *
     * @returns {Modal}
     */
    getModal()
    {
        console.log(2)
        if (!this.modal) {
            this.modal = new Modal(this.element.querySelector('.modal'), {backdrop: 'static', keyboard: false});
        }

        return this.modal;
    }

    /**
     * Event for uploading image
     *
     * @param event
     */
    upload(event) {
        console.log(3)
        let maxFileSize = this.data.get('max-file-size');
        if (maxFileSize !== null && event.target.files[0].size / 1024 / 1024 > maxFileSize) {
            this.alert('Validation error', `The download file is too large. Max size: ${maxFileSize} MB`);
            event.target.value = null;
            return;
        }

        if (!event.target.files[0]) {
            this.getModal().show();
            return;
        }

        let reader = new FileReader();
        reader.readAsDataURL(event.target.files[0]);

        reader.onloadend = () => {
            this.cropper.replace(reader.result)
        };

        this.getModal().show();
    }

    /**
     *
     */
    openModal(event)
    {
        console.log(4)
        if (!event.target.files[0]) {
            return;
        }

        this.getModal().show();
    }

    /**
     * Action on click button "Crop"
     */
    crop() {
        console.log(5)
        let data = this.cropper.getData();

        this.cropper.getCroppedCanvas().toBlob((blob) => {
            const formData = new FormData();
            formData.append('file', blob);
            formData.append('storage', this.data.get('storage'));
            formData.append('group', this.data.get('groups'));
            formData.append('path', this.data.get('path'));
            formData.append('acceptedFiles', this.data.get('accepted-files'));
            formData.append('width', this.data.get('width'));
            formData.append('height', this.data.get('height'));

            let element = this.element;
            window.axios.post(this.prefix('/systems/files'), formData)
                .then((response) => {
                    let image = response.data.url;
                    let targetValue = this.data.get('target');

                    element.querySelector('.cropper-preview').src = image;
                    element.querySelector('.cropper-preview').classList.remove('none');
                    element.querySelector('.cropper-remove').classList.remove('none');
                    element.querySelector('.cropper-path').value = response.data[targetValue];

                    // add event for listener
                    element.querySelector('.cropper-path').dispatchEvent(new Event("change"));

                    this.getModal().hide();
                })
                .catch((error) => {
                    this.alert('Validation error', 'File upload error');
                    console.warn(error);
                });
        }, 'image/jpeg');

    }

    /**
     *
     */
    clear() {
        console.log(6)
        this.element.querySelector('.cropper-path').value = '';
        this.element.querySelector('.cropper-preview').src = '';
        this.element.querySelector('.cropper-preview').classList.add('none');
        this.element.querySelector('.cropper-remove').classList.add('none');
    }

    /**
     * Action on click buttons
     */
    moveleft() {
        this.cropper.move(-10, 0);
    }

    moveright() {
        this.cropper.move(10, 0);
    }

    moveup() {
        this.cropper.move(0, -10);
    }

    movedown() {
        this.cropper.move(0, 10);
    }

    zoomin() {
        this.cropper.zoom(0.1);
    }

    zoomout() {
        this.cropper.zoom(-0.1);
    }

    rotateleft() {
        this.cropper.rotate(-5);
    }

    rotateright() {
        this.cropper.rotate(5);
    }

    scalex() {
        const dataScaleX = this.element.querySelector('.cropper-dataScaleX');
        this.cropper.scaleX(-dataScaleX.value);
    }

    scaley() {
        const dataScaleY = this.element.querySelector('.cropper-dataScaleY');
        this.cropper.scaleY(-dataScaleY.value)
    }

    aspectratiowh() {
        this.cropper.setAspectRatio(this.data.get('width') / this.data.get('height'));
    }

    aspectratiofree() {
        this.cropper.setAspectRatio(NaN);
    }

}

application.register("cropperrocont", CropperRocont);
