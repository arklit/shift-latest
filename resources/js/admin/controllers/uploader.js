import {Dropzone} from 'dropzone';
import Sortable from 'sortablejs';
import {Modal} from "bootstrap/js/dist/modal";

export default class UploaderRocont extends window.Controller {
    /**
     *
     * @type {string[]}
     */
    static targets = [
        'search',
        'name',
        'original',
        'alt',
        'description',
        'url',
        'loadmore',
    ];

    /**
     *
     * @param props
     */
    constructor(props) {
        super(props);
        this.attachments = {};
        this.mediaList = [];
        this.allMediaList = {};
    }

    initialize() {
        this.page = 1
    }

    /**
     *
     * @returns {string}
     */
    get dropname() {
        return this.element.querySelector('#' + this.element.dataset.uploadId);
    }

    /**
     *
     * @returns {string|{id: *}}
     */
    get activeAttachment() {
        return {
            id: this.activeAchivmentId,
            name: this[this.getAttachmentTargetKey('name')].value || '',
            alt: this[this.getAttachmentTargetKey('alt')].value || '',
            description: this[this.getAttachmentTargetKey('description')].value || '',
            original_name: this[this.getAttachmentTargetKey('original')].value || '',
        };
    }

    /**
     *
     * @param data
     */
    set activeAttachment(data) {
        this.activeAchivmentId = data.id;

        this[this.getAttachmentTargetKey('name')].value = data.name || '';
        this[this.getAttachmentTargetKey('original')].value = data.original_name || '';
        this[this.getAttachmentTargetKey('alt')].value = data.alt || '';
        this[this.getAttachmentTargetKey('description')].value = data.description || '';

        this.data.set('url', data.url);
    }

    /**
     *
     */
    openLink(event) {
        event.preventDefault();
        window.open(this.data.get('url'));
    }

    /**
     *
     */
    connect() {
        this.initDropZone();
        this.initSortable();
    }

    /**
     *
     */
    save() {
        const attach = this.activeAttachment;
        const dropname = this.dropname;

        let loadMediaModal = Modal.getOrCreateInstance(dropname.querySelector(`.attachment.modal`));
        loadMediaModal.toggle();

        const name = attach.name + attach.id;

        if (this.attachments.hasOwnProperty(name)) {
            this.attachments[name].name = attach.name;
            this.attachments[name].alt = attach.alt;
            this.attachments[name].description = attach.description;
            this.attachments[name].original_name = attach.original_name;
        }

        axios
            .put(this.prefix(`/systems/files/post/${attach.id}`), attach)
            .then();
    }

    /**
     *
     * @param dataKey
     * @returns {string}
     */
    getAttachmentTargetKey(dataKey) {
        return `${dataKey}Target`;
    }

    /**
     *
     * @param data
     */
    loadInfo(data) {
        const name = data.name + data.id;

        if (!this.attachments.hasOwnProperty(name)) {
            this.attachments[name] = data;
        }
        this.activeAttachment = data;
    }

    /**
     *
     */
    resortElement() {
        const items = {};
        const self = this;
        const dropname = this.dropname;
        const CancelToken = axios.CancelToken;

        if (typeof this.cancelRequest === 'function') {
            this.cancelRequest();
        }

        let elements = dropname.querySelectorAll(`:scope .file-sort`);
        elements.forEach((value, index) => {
            const id = value.getAttribute('data-file-id');
            items[id] = index;
        });

        axios
            .post(this.prefix('/systems/files/sort'), {
                files: items,
            }, {
                cancelToken: new CancelToken(function executor(c) {
                    self.cancelRequest = c;
                }),
            })
            .then();
    }

    /**
     *
     */
    initSortable() {
        new Sortable(this.element.querySelector('.sortable-dropzone'), {
            animation: 150,
            onEnd: () => {
                this.resortElement();
            },
        });
    }

    /**
     *
     * @param dropname
     * @param name
     * @param file
     */
    addSortDataAtributes(dropname, name, file) {

        const items = dropname.querySelectorAll(' .dz-complete');

        if (items !== null && items.item(items.length - 1) !== null) {
            items.item(items.length - 1).setAttribute('data-file-id', file.id);
            items.item(items.length - 1).classList.add('file-sort');
        }

        const node = document.createElement('input');
        node.setAttribute('type', 'hidden');
        node.setAttribute('name', name + '[]');
        node.setAttribute('value', file.id);
        node.classList.add('files-' + file.id);
        dropname.appendChild(node);

    }

    /**
     *
     */
    initDropZone() {
        const self = this;
        const id = this.element.dataset.uploadId;
        const data = this.element.dataset.uploadData && JSON.parse(this.element.dataset.uploadData);
        const storage = this.element.dataset.uploadStorage;
        const name = this.element.dataset.uploadName;
        const loadInfo = this.loadInfo.bind(this);
        const dropname = this.dropname;
        const groups = this.element.dataset.uploadGroups;
        const uploadPath = this.element.dataset.uploadPath;
        const multiple = this.element.dataset.uploadMultiple;
        const isMediaLibrary = this.element.dataset.uploadIsMediaLibrary;

        const removeButtonTemplate = this.element.querySelector('#' + id + '-remove-button').innerHTML.trim();
        const editButtonTemplate = this.element.querySelector('#' + id + '-edit-button').innerHTML.trim();

        const controller = this;

        const urlDelete = this.prefix(`/systems/files/`);
        console.log(data)
        this.dropZone = new Dropzone(this.element.querySelector('#' + id), {
            url: this.prefix('/systems/files'),
            method: 'post',
            uploadMultiple: true,
            maxFilesize: this.element.dataset.uploadMaxFileSize,
            maxFiles: multiple ? this.element.dataset.uploadMaxFiles : 1,
            timeout: this.element.dataset.uploadTimeout,
            acceptedFiles: this.element.dataset.uploadAcceptedFiles,
            resizeQuality: this.element.dataset.uploadResizeQuality,
            resizeWidth: this.element.dataset.uploadResizeWidth,
            resizeHeight: this.element.dataset.uploadResizeHeight,
            paramName: 'files',

            previewsContainer: dropname.querySelector('.visual-dropzone'),
            addRemoveLinks: false,
            dictFileTooBig: 'File is big',
            autoDiscover: false,

            init: function () {

                this.on('addedfile', (e) => {
                    if (this.files.length > this.options.maxFiles) {
                        controller.alert('Ошибка проверки', 'Максимум файлов');
                        this.removeFile(e);
                    }

                    const editButton = Dropzone.createElement(editButtonTemplate);
                    const removeButton = Dropzone.createElement(removeButtonTemplate);

                    removeButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        this.removeFile(e);
                    });

                    editButton.addEventListener('click', () => {
                        loadInfo(e.data);

                        const attachmentModal = Modal.getOrCreateInstance(dropname.querySelector(`.attachment.modal`));
                        attachmentModal.show();
                    });

                    e.previewElement.appendChild(removeButton);
                    e.previewElement.appendChild(editButton);
                });

                this.on("maxfilesexceeded", (file) => {
                    controller.alert('Ошибка проверки', 'Превышено максимальное количество файлов');
                    this.removeFile(file);
                });

                this.on('sending', (file, xhr, formData) => {
                    let token = document.querySelector('meta[name=\'csrf_token\']').getAttribute('content')
                    formData.append('_token', token);
                    formData.append('storage', storage);
                    formData.append('group', groups);
                    formData.append('path', uploadPath);
                });

                this.on('removedfile', file => {
                    if (file.hasOwnProperty('data') && file.data.hasOwnProperty('id')) {
                        let removeItem = dropname.querySelector(`.files-${file.data.id}`);
                        if (removeItem !== null && removeItem.parentNode !== null) {
                            removeItem.parentNode.removeChild(removeItem);
                        }
                    }
                });

                if (!multiple) {
                    this.hiddenFileInput.removeAttribute('multiple');
                }

                const images = data;

                if (images) {
                    Object.values(images).forEach((item) => {
                        const file = {
                            id: item.id,
                            name: item.original_name,
                            size: item.size,
                            type: item.mime,
                            status: Dropzone.ADDED,
                            url: `${item.url}`,
                            data: item,
                        };

                        this.emit('addedfile', file);
                        this.emit('thumbnail', file, file.url);
                        this.emit('complete', file);
                        this.files.push(file);
                        self.addSortDataAtributes(dropname, name, item);
                    });
                }

                let removeItem = dropname.querySelector(`.dz-progress`);
                if (removeItem !== null && removeItem.parentNode !== null) {
                    removeItem.parentNode.removeChild(removeItem);
                }
            },
            error(file, response) {
                controller.alert('Ошибка валидации', 'Ошибка загрузки файла');

                this.removeFile(file);

                if (Object.prototype.toString.call(response).replace(/^\[object (.+)\]$/, '$1').toLowerCase() === 'string') {
                    return response;
                }
                return response.message;
            },
            success(file, response) {

                if (!Array.isArray(response)) {
                    response = [response];
                }

                response.forEach((item) => {
                    if (file.name === item.original_name) {
                        file.data = item;
                        return false;
                    }
                });

                self.addSortDataAtributes(dropname, name, file.data);
                self.resortElement();
            },
        });
    }

    /**
     *
     */
    openMedia() {
        const dropname = this.dropname;
        dropname.querySelector('.media-loader').style.display = "";
        dropname.querySelector('.media-results').style.display = "none";

        this.resetPage();
        this.loadMedia();
    }

    /**
     *
     */
    loadMore(event) {
        event.preventDefault();
        this.page++;
        this.loadMedia();
    }

    /**
     *
     */
    resetPage() {
        this.allMediaList = {}; // Reset all media list
        this.page = 1; // Reset page

        this.dropname.querySelector(`.media-results`).innerHTML = "";
    }

    /**
     *
     */
    loadMedia() {
        const self = this;
        const CancelToken = axios.CancelToken;
        const dropname = this.dropname;

        if (typeof this.cancelRequest === 'function') {
            this.cancelRequest();
        }

        let loadMediaModal = Modal.getOrCreateInstance(dropname.querySelector(`.media.modal`));
        loadMediaModal.show();


        let filter = {
            disk: this.element.dataset.uploadStorage,
            original_name: this.searchTarget.value,
            group: this.element.dataset.uploadGroups || null,
        };

        Object.keys(filter).forEach((key) => (filter[key] === null) && delete filter[key]);

        axios
            .post(this.prefix(`/systems/media?page=${this.page}`), {
                filter
            }, {
                cancelToken: new CancelToken(function executor(c) {
                    self.cancelRequest = c;
                }),
            })
            .then((response) => {
                this.mediaList = response.data.data;
                // show/hide load more
                this.loadmoreTarget.classList.toggle('d-none', response.data.last_page === this.page);
                this.renderMedia();
            });
    }

    /**
     *
     */
    renderMedia() {
        this.mediaList.forEach((element, key) => {
            const index = this.page + '-' + key;

            const template = this.element
                .querySelector('#' + this.data.get('id') + '-media')
                .content
                .querySelector('.media-item')
                .cloneNode(true);

            template.innerHTML = template.innerHTML
                .replace(/{index}/, index)
                .replace(/{element.url}/, element.url)
                .replace(/{element.original_name}/, element.original_name)
                .replace(/{element.original_name}/, element.original_name);

            this.dropname.querySelector(`.media-results`).appendChild(template);
            this.allMediaList[index] = element;
        });

        this.dropname.querySelector('.media-loader').style.display = "none";
        this.dropname.querySelector('.media-results').style.display = "";
    }

    /**
     *
     */
    addFile(event) {
        const key = event.currentTarget.dataset.key;
        const file = this.allMediaList[key]

        this.addedExistFile(file);

        if (this.data.get('close-on-add')) {
            let loadMediaModal = Modal.getOrCreateInstance(this.dropname.querySelector(`.media.modal`));
            loadMediaModal.hide();
        }
    }

    /**
     *
     * @param attachment
     */
    addedExistFile(attachment) {
        const multiple = !!this.element.dataset.uploadMultiple;
        const maxFiles = multiple ? this.element.dataset.uploadMaxFiles : 1;

        if (this.dropZone.files.length >= maxFiles) {
            this.alert('Превышено максимальное количество файлов');
            return;
        }

        /** todo: Дублируется дважды */
        const file = {
            id: attachment.id,
            name: attachment.original_name,
            size: attachment.size,
            type: attachment.mime,
            status: Dropzone.ADDED,
            url: `${attachment.url}`,
            data: attachment,
        };

        this.dropZone.emit('addedfile', file);
        this.dropZone.emit('thumbnail', file, file.url);
        this.dropZone.emit('complete', file);
        this.dropZone.files.push(file);
        this.addSortDataAtributes(this.dropname, this.element.dataset.uploadName, file);
        this.resortElement();
    }
}
