/*global Util*/

var Events;

(function () {
    'use strict';

    Events = function (instance) {
        this.base = instance;
        this.options = this.base.options;
        this.events = [];
        this.customEvents = {};
        this.listeners = {};
    };

    Events.prototype = {
        InputEventOnContenteditableSupported: !Util.isIE,

        // Helpers for event handling

        attachDOMEvent: function (target, event, listener, useCapture) {
            target.addEventListener(event, listener, useCapture);
            this.events.push([target, event, listener, useCapture]);
        },

        detachDOMEvent: function (target, event, listener, useCapture) {
            var index = this.indexOfListener(target, event, listener, useCapture),
                e;
            if (index !== -1) {
                e = this.events.splice(index, 1)[0];
                e[0].removeEventListener(e[1], e[2], e[3]);
            }
        },

        indexOfListener: function (target, event, listener, useCapture) {
            var i, n, item;
            for (i = 0, n = this.events.length; i < n; i = i + 1) {
                item = this.events[i];
                if (item[0] === target && item[1] === event && item[2] === listener && item[3] === useCapture) {
                    return i;
                }
            }
            return -1;
        },

        detachAllDOMEvents: function () {
            var e = this.events.pop();
            while (e) {
                e[0].removeEventListener(e[1], e[2], e[3]);
                e = this.events.pop();
            }
        },

        // custom events
        attachCustomEvent: function (event, listener) {
            this.setupListener(event);
            if (!this.customEvents[event]) {
                this.customEvents[event] = [];
            }
            this.customEvents[event].push(listener);
        },

        detachCustomEvent: function (event, listener) {
            var index = this.indexOfCustomListener(event, listener);
            if (index !== -1) {
                this.customEvents[event].splice(index, 1);
                // TODO: If array is empty, should detach internal listeners via destroyListener()
            }
        },

        indexOfCustomListener: function (event, listener) {
            if (!this.customEvents[event] || !this.customEvents[event].length) {
                return -1;
            }

            return this.customEvents[event].indexOf(listener);
        },

        detachAllCustomEvents: function () {
            this.customEvents = {};
            // TODO: Should detach internal listeners here via destroyListener()
        },

        triggerCustomEvent: function (name, data, editable) {
            if (this.customEvents[name]) {
                this.customEvents[name].forEach(function (listener) {
                    listener(data, editable);
                });
            }
        },

        // Cleaning up

        destroy: function () {
            this.detachAllDOMEvents();
            this.detachAllCustomEvents();
            this.detachExecCommand();
        },

        // Listening to calls to document.execCommand

        // Attach a listener to be notified when document.execCommand is called
        attachToExecCommand: function () {
            if (this.execCommandListener) {
                return;
            }

            // Store an instance of the listener so:
            // 1) We only attach to execCommand once
            // 2) We can remove the listener later
            this.execCommandListener = function (execInfo) {
                this.handleDocumentExecCommand(execInfo);
            }.bind(this);

            // Ensure that execCommand has been wrapped correctly
            this.wrapExecCommand();

            // Add listener to list of execCommand listeners
            this.options.ownerDocument.execCommand.listeners.push(this.execCommandListener);
        },

        // Remove our listener for calls to document.execCommand
        detachExecCommand: function () {
            var doc = this.options.ownerDocument;
            if (!this.execCommandListener || !doc.execCommand.listeners) {
                return;
            }

            // Find the index of this listener in the array of listeners so it can be removed
            var index = doc.execCommand.listeners.indexOf(this.execCommandListener);
            if (index !== -1) {
                doc.execCommand.listeners.splice(index, 1);
            }

            // If the list of listeners is now empty, put execCommand back to its original state
            if (!doc.execCommand.listeners.length) {
                this.unwrapExecCommand();
            }
        },

        // Wrap document.execCommand in a custom method so we can listen to calls to it
        wrapExecCommand: function () {
            var doc = this.options.ownerDocument;

            // Ensure all instance of MediumEditor only wrap execCommand once
            if (doc.execCommand.listeners) {
                return;
            }

            // Create a wrapper method for execCommand which will:
            // 1) Call document.execCommand with the correct arguments
            // 2) Loop through any listeners and notify them that execCommand was called
            //    passing extra info on the call
            // 3) Return the result
            var wrapper = function (aCommandName, aShowDefaultUI, aValueArgument) {
                var result = doc.execCommand.orig.apply(this, arguments);

                if (!doc.execCommand.listeners) {
                    return result;
                }

                var args = Array.prototype.slice.call(arguments);
                doc.execCommand.listeners.forEach(function (listener) {
                    listener({
                        command: aCommandName,
                        value: aValueArgument,
                        args: args,
                        result: result
                    });
                });

                return result;
            };

            // Store a reference to the original execCommand
            wrapper.orig = doc.execCommand;

            // Attach an array for storing listeners
            wrapper.listeners = [];

            // Overwrite execCommand
            doc.execCommand = wrapper;
        },

        // Revert document.execCommand back to its original self
        unwrapExecCommand: function () {
            var doc = this.options.ownerDocument;
            if (!doc.execCommand.orig) {
                return;
            }

            // Use the reference to the original execCommand to revert back
            doc.execCommand = doc.execCommand.orig;
        },

        // Listening to browser events to emit events medium-editor cares about
        setupListener: function (name) {
            if (this.listeners[name]) {
                return;
            }

            switch (name) {
                case 'externalInteraction':
                    // Detecting when user has interacted with elements outside of MediumEditor
                    this.attachDOMEvent(this.options.ownerDocument.body, 'mousedown', this.handleBodyMousedown.bind(this), true);
                    this.attachDOMEvent(this.options.ownerDocument.body, 'click', this.handleBodyClick.bind(this), true);
                    this.attachDOMEvent(this.options.ownerDocument.body, 'focus', this.handleBodyFocus.bind(this), true);
                    break;
                case 'blur':
                    // Detecting when focus is lost
                    this.setupListener('externalInteraction');
                    break;
                case 'focus':
                    // Detecting when focus moves into some part of MediumEditor
                    this.setupListener('externalInteraction');
                    break;
                case 'editableInput':
                    // setup cache for knowing when the content has changed
                    this.contentCache = [];
                    this.base.elements.forEach(function (element) {
                        this.contentCache[element.getAttribute('medium-editor-index')] = element.innerHTML;

                        // Attach to the 'oninput' event, handled correctly by most browsers
                        if (this.InputEventOnContenteditableSupported) {
                            this.attachDOMEvent(element, 'input', this.handleInput.bind(this));
                        }
                    }.bind(this));

                    // For browsers which don't support the input event on contenteditable (IE)
                    // we'll attach to 'selectionchange' on the document and 'keypress' on the editables
                    if (!this.InputEventOnContenteditableSupported) {
                        this.setupListener('editableKeypress');
                        this.keypressUpdateInput = true;
                        this.attachDOMEvent(document, 'selectionchange', this.handleDocumentSelectionChange.bind(this));
                        // Listen to calls to execCommand
                        this.attachToExecCommand();
                    }
                    break;
                case 'editableClick':
                    // Detecting click in the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'click', this.handleClick.bind(this));
                    }.bind(this));
                    break;
                case 'editableBlur':
                    // Detecting blur in the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'blur', this.handleBlur.bind(this));
                    }.bind(this));
                    break;
                case 'editableKeypress':
                    // Detecting keypress in the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'keypress', this.handleKeypress.bind(this));
                    }.bind(this));
                    break;
                case 'editableKeyup':
                    // Detecting keyup in the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'keyup', this.handleKeyup.bind(this));
                    }.bind(this));
                    break;
                case 'editableKeydown':
                    // Detecting keydown on the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'keydown', this.handleKeydown.bind(this));
                    }.bind(this));
                    break;
                case 'editableKeydownEnter':
                    // Detecting keydown for ENTER on the contenteditables
                    this.setupListener('editableKeydown');
                    break;
                case 'editableKeydownTab':
                    // Detecting keydown for TAB on the contenteditable
                    this.setupListener('editableKeydown');
                    break;
                case 'editableKeydownDelete':
                    // Detecting keydown for DELETE/BACKSPACE on the contenteditables
                    this.setupListener('editableKeydown');
                    break;
                case 'editableMouseover':
                    // Detecting mouseover on the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'mouseover', this.handleMouseover.bind(this));
                    }, this);
                    break;
                case 'editableDrag':
                    // Detecting dragover and dragleave on the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'dragover', this.handleDragging.bind(this));
                        this.attachDOMEvent(element, 'dragleave', this.handleDragging.bind(this));
                    }, this);
                    break;
                case 'editableDrop':
                    // Detecting drop on the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'drop', this.handleDrop.bind(this));
                    }, this);
                    break;
                case 'editablePaste':
                    // Detecting paste on the contenteditables
                    this.base.elements.forEach(function (element) {
                        this.attachDOMEvent(element, 'paste', this.handlePaste.bind(this));
                    }, this);
                    break;
            }
            this.listeners[name] = true;
        },

        focusElement: function (element) {
            element.focus();
            this.updateFocus(element, { target: element, type: 'focus' });
        },

        updateFocus: function (target, eventObj) {
            var toolbarEl = this.base.toolbar ? this.base.toolbar.getToolbarElement() : null,
                anchorPreview = this.base.getExtensionByName('anchor-preview'),
                previewEl = (anchorPreview && anchorPreview.getPreviewElement) ? anchorPreview.getPreviewElement() : null,
                hadFocus = this.base.getFocusedElement(),
                toFocus;

            // For clicks, we need to know if the mousedown that caused the click happened inside the existing focused element.
            // If so, we don't want to focus another element
            if (hadFocus &&
                    eventObj.type === 'click' &&
                    this.lastMousedownTarget &&
                    (Util.isDescendant(hadFocus, this.lastMousedownTarget, true) ||
                     Util.isDescendant(toolbarEl, this.lastMousedownTarget, true) ||
                     Util.isDescendant(previewEl, this.lastMousedownTarget, true))) {
                toFocus = hadFocus;
            }

            if (!toFocus) {
                this.base.elements.some(function (element) {
                    // If the target is part of an editor element, this is the element getting focus
                    if (!toFocus && (Util.isDescendant(element, target, true))) {
                        toFocus = element;
                    }

                    // bail if we found an element that's getting focus
                    return !!toFocus;
                }, this);
            }

            // Check if the target is external (not part of the editor, toolbar, or anchorpreview)
            var externalEvent = !Util.isDescendant(hadFocus, target, true) &&
                                !Util.isDescendant(toolbarEl, target, true) &&
                                !Util.isDescendant(previewEl, target, true);

            if (toFocus !== hadFocus) {
                // If element has focus, and focus is going outside of editor
                // Don't blur focused element if clicking on editor, toolbar, or anchorpreview
                if (hadFocus && externalEvent) {
                    // Trigger blur on the editable that has lost focus
                    hadFocus.removeAttribute('data-medium-focused');
                    this.triggerCustomEvent('blur', eventObj, hadFocus);
                }

                // If focus is going into an editor element
                if (toFocus) {
                    // Trigger focus on the editable that now has focus
                    toFocus.setAttribute('data-medium-focused', true);
                    this.triggerCustomEvent('focus', eventObj, toFocus);
                }
            }

            if (externalEvent) {
                this.triggerCustomEvent('externalInteraction', eventObj);
            }
        },

        updateInput: function (target, eventObj) {
            // An event triggered which signifies that the user may have changed someting
            // Look in our cache of input for the contenteditables to see if something changed
            var index = target.getAttribute('medium-editor-index');
            if (target.innerHTML !== this.contentCache[index]) {
                // The content has changed since the last time we checked, fire the event
                this.triggerCustomEvent('editableInput', eventObj, target);
            }
            this.contentCache[index] = target.innerHTML;
        },

        handleDocumentSelectionChange: function (event) {
            // When selectionchange fires, target and current target are set
            // to document, since this is where the event is handled
            // However, currentTarget will have an 'activeElement' property
            // which will point to whatever element has focus.
            if (event.currentTarget && event.currentTarget.activeElement) {
                var activeElement = event.currentTarget.activeElement,
                    currentTarget;
                // We can look at the 'activeElement' to determine if the selectionchange has
                // happened within a contenteditable owned by this instance of MediumEditor
                this.base.elements.some(function (element) {
                    if (Util.isDescendant(element, activeElement, true)) {
                        currentTarget = element;
                        return true;
                    }
                    return false;
                }, this);

                // We know selectionchange fired within one of our contenteditables
                if (currentTarget) {
                    this.updateInput(currentTarget, { target: activeElement, currentTarget: currentTarget });
                }
            }
        },

        handleDocumentExecCommand: function () {
            // document.execCommand has been called
            // If one of our contenteditables currently has focus, we should
            // attempt to trigger the 'editableInput' event
            var target = this.base.getFocusedElement();
            if (target) {
                this.updateInput(target, { target: target, currentTarget: target });
            }
        },

        handleBodyClick: function (event) {
            this.updateFocus(event.target, event);
        },

        handleBodyFocus: function (event) {
            this.updateFocus(event.target, event);
        },

        handleBodyMousedown: function (event) {
            this.lastMousedownTarget = event.target;
        },

        handleInput: function (event) {
            this.updateInput(event.currentTarget, event);
        },

        handleClick: function (event) {
            this.triggerCustomEvent('editableClick', event, event.currentTarget);
        },

        handleBlur: function (event) {
            this.triggerCustomEvent('editableBlur', event, event.currentTarget);
        },

        handleKeypress: function (event) {
            this.triggerCustomEvent('editableKeypress', event, event.currentTarget);

            // If we're doing manual detection of the editableInput event we need
            // to check for input changes during 'keypress'
            if (this.keypressUpdateInput) {
                var eventObj = { target: event.target, currentTarget: event.currentTarget };

                // In IE, we need to let the rest of the event stack complete before we detect
                // changes to input, so using setTimeout here
                setTimeout(function () {
                    this.updateInput(eventObj.currentTarget, eventObj);
                }.bind(this), 0);
            }
        },

        handleKeyup: function (event) {
            this.triggerCustomEvent('editableKeyup', event, event.currentTarget);
        },

        handleMouseover: function (event) {
            this.triggerCustomEvent('editableMouseover', event, event.currentTarget);
        },

        handleDragging: function (event) {
            this.triggerCustomEvent('editableDrag', event, event.currentTarget);
        },

        handleDrop: function (event) {
            this.triggerCustomEvent('editableDrop', event, event.currentTarget);
        },

        handlePaste: function (event) {
            this.triggerCustomEvent('editablePaste', event, event.currentTarget);
        },

        handleKeydown: function (event) {
            this.triggerCustomEvent('editableKeydown', event, event.currentTarget);

            if (Util.isKey(event, Util.keyCode.ENTER)) {
                return this.triggerCustomEvent('editableKeydownEnter', event, event.currentTarget);
            }

            if (Util.isKey(event, Util.keyCode.TAB)) {
                return this.triggerCustomEvent('editableKeydownTab', event, event.currentTarget);
            }

            if (Util.isKey(event, [Util.keyCode.DELETE, Util.keyCode.BACKSPACE])) {
                return this.triggerCustomEvent('editableKeydownDelete', event, event.currentTarget);
            }
        }
    };
}());
