/**
 * Optimierter Repeater mit virtualization und lazy-loading
 * Verbesserte Performance für viele Items
 */

define(['jquery'], function($) {

    /**
     * Virtual Repeater für große Mengen an Elementen
     * Rendert nur die sichtbaren Items
     */
    var VirtualRepeater = function(container) {
        this.container = $(container);
        this.items = [];
        this.itemHeight = 200; // Geschätzte Höhe
        this.visibleRange = 5; // Anzahl der sichtbaren Items
        this.scrollBuffer = 2; // Extra Items zum Laden
        this.currentStart = 0;
    };

    VirtualRepeater.prototype.init = function() {
        var self = this;
        
        // Optimiertes Event-Delegating statt Event-Binding für alle Elemente
        this.container.on('click', '.repeater-item-remove', function(e) {
            e.preventDefault();
            self.removeItem($(this).closest('.repeater-group'));
        });

        this.container.on('change', 'input, select, textarea', function() {
            // Debounce data updates
            clearTimeout(self.updateTimeout);
            self.updateTimeout = setTimeout(function() {
                self.updateItemData($(this).closest('.repeater-group'));
            }, 500);
        });

        // Scroll-Virtualization für große Listen
        this.container.parent().on('scroll', function() {
            requestAnimationFrame(function() {
                self.handleScroll();
            });
        });
    };

    VirtualRepeater.prototype.addItem = function(data) {
        this.items.push(data);
        this.renderItems();
    };

    VirtualRepeater.prototype.removeItem = function($item) {
        var index = $item.index();
        this.items.splice(index, 1);
        this.renderItems();
    };

    VirtualRepeater.prototype.handleScroll = function() {
        var container = this.container.parent();
        var scrollTop = container.scrollTop();
        var start = Math.floor(scrollTop / this.itemHeight) - this.scrollBuffer;
        
        if (start !== this.currentStart && start >= 0) {
            this.currentStart = Math.max(0, start);
            this.renderItems();
        }
    };

    VirtualRepeater.prototype.renderItems = function() {
        var self = this;
        var end = this.currentStart + this.visibleRange + (this.scrollBuffer * 2);

        // Batch-DOM-Updates statt einzelner Manipulationen
        var fragment = document.createDocumentFragment();
        
        for (var i = this.currentStart; i < Math.min(end, this.items.length); i++) {
            var item = this.items[i];
            var itemEl = this.renderItemElement(item, i);
            fragment.appendChild(itemEl[0]);
        }

        // Single DOM reflow statt vielen
        this.container.empty().append(fragment);
    };

    VirtualRepeater.prototype.renderItemElement = function(data, index) {
        // Lazy-Load Bilder
        var imageHtml = '';
        if (data.image) {
            imageHtml = '<img src="' + data.image + '" loading="lazy" alt="Slide ' + index + '" />';
        }

        return $('<div class="repeater-group">\
            <div class="repeater-item">\
                ' + imageHtml + '\
                <input type="hidden" name="images[' + index + '][image]" value="' + (data.image || '') + '" />\
                <div class="repeater-controls">\
                    <button type="button" class="button repeater-item-remove">Remove</button>\
                </div>\
            </div>\
        </div>');
    };

    VirtualRepeater.prototype.updateItemData = function($item) {
        var index = $item.index();
        // Update nur das betroffene Item
        if (this.items[index]) {
            this.items[index] = this.getItemData($item);
        }
    };

    VirtualRepeater.prototype.getItemData = function($item) {
        var data = {};
        $item.find('input, select, textarea').each(function() {
            var $input = $(this);
            var name = $input.attr('name');
            if (name) {
                data[name] = $input.val();
            }
        });
        return data;
    };

    /**
     * Optimierte Image-Uploader mit Drag-and-Drop
     */
    var OptimizedImageUploader = function(container) {
        this.container = $(container);
        this.uploads = [];
        this.uploading = false;
    };

    OptimizedImageUploader.prototype.init = function() {
        var self = this;

        // Drag-and-Drop Handler
        this.container.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragging');
        }).on('dragleave', function() {
            $(this).removeClass('dragging');
        }).on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragging');
            self.handleDrop(e.originalEvent.dataTransfer.files);
        });

        // File Input Handler
        this.container.on('change', 'input[type="file"]', function() {
            self.handleFileSelect(this.files);
        });
    };

    OptimizedImageUploader.prototype.handleDrop = function(files) {
        this.handleFileSelect(files);
    };

    OptimizedImageUploader.prototype.handleFileSelect = function(files) {
        var self = this;
        
        // Batch-Upload statt einzelner Uploads
        if (files.length === 0) return;

        this.uploading = true;
        this.showProgress(0, files.length);

        var formData = new FormData();
        formData.append('action', 'padma_upload_image');
        formData.append('nonce', padmaMediaUploader.nonce);

        // Alle Dateien zu FormData hinzufügen
        for (var i = 0; i < files.length; i++) {
            formData.append('file[]', files[i]);
        }

        // AJAX Smart Upload mit Fortschritt
        $.ajax({
            url: padmaMediaUploader.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                
                // Upload-Fortschritt
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        self.showProgress(percentComplete, files.length);
                    }
                }, false);
                
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    response.data.uploads.forEach(function(upload) {
                        self.addUploadedImage(upload);
                    });
                    self.showMessage('success', 'Bilder wurden erfolgreich hochgeladen.');
                } else {
                    self.showMessage('error', response.data.message);
                }
                self.uploading = false;
                self.showProgress(100, files.length);
            },
            error: function(xhr, status, error) {
                self.showMessage('error', 'Upload fehlgeschlagen: ' + error);
                self.uploading = false;
            }
        });
    };

    OptimizedImageUploader.prototype.addUploadedImage = function(uploadData) {
        // Trigger event für das Parent-System
        this.container.trigger('image.uploaded', uploadData);
    };

    OptimizedImageUploader.prototype.showProgress = function(current, total) {
        var percent = Math.round((current / total) * 100);
        var progressBar = this.container.find('.upload-progress-bar');
        
        if (progressBar.length === 0) {
            progressBar = $('<div class="upload-progress">\
                <div class="upload-progress-bar"></div>\
                <span class="upload-progress-text">0%</span>\
            </div>');
            this.container.append(progressBar);
            progressBar = this.container.find('.upload-progress-bar');
        }

        progressBar.css('width', percent + '%');
        this.container.find('.upload-progress-text').text(percent + '%');

        if (percent >= 100) {
            setTimeout(function() {
                progressBar.closest('.upload-progress').fadeOut(function() {
                    $(this).remove();
                });
            }, 1000);
        }
    };

    OptimizedImageUploader.prototype.showMessage = function(type, message) {
        var messageEl = $('<div class="upload-message upload-message-' + type + '">' + message + '</div>');
        this.container.append(messageEl);
        
        setTimeout(function() {
            messageEl.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    };

    // Export
    return {
        VirtualRepeater: VirtualRepeater,
        OptimizedImageUploader: OptimizedImageUploader
    };
});
