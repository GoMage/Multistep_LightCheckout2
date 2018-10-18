
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'ko'
    ],
    function($, ko) {
        var steps = ko.observableArray();
        return {
            steps: steps,
            stepCodes: [],
            validCodes: [],

            /**
             *
             * @param {string} code
             * @param {string} alias
             */
            unRegisterStep: function (code, alias) {
                var i;

                for (i = 0; i < this.steps().length; i++) {
                    if (this.steps()[i].code === code) {
                        this.steps().splice(this.steps().indexOf(code), 1);
                    }
                }

                for (i = 0; i < this.stepCodes.length; i++) {
                    if (this.stepCodes === code) {
                        this.stepCodes.splice(this.steps().indexOf(code), 1);
                    }
                }

                for (i = 0; i < this.validCodes.length; i++) {
                    if (this.validCodes === code) {
                        this.validCodes.splice(this.steps().indexOf(code), 1);
                    }
                    if (this.validCodes === alias) {
                        this.stepCodes.splice(this.steps().indexOf(alias), 1);
                    }
                }
            },

            registerStep: function(code, alias, title, isVisible, navigate, sortOrder) {
                if (-1 != $.inArray(code, this.validCodes)) {
                    throw new DOMException('Step code [' + code + '] already registered in step navigator');
                }
                if (alias != null) {
                    if (-1 != $.inArray(alias, this.validCodes)) {
                        throw new DOMException('Step code [' + alias + '] already registered in step navigator');
                    }
                    this.validCodes.push(alias);
                }
                this.validCodes.push(code);
                steps.push({
                    code: code,
                    alias: alias != null ? alias : code,
                    title : title,
                    isVisible: isVisible,
                    navigate: navigate,
                    sortOrder: sortOrder
                });
                this.stepCodes.push(code);
            },

            sortItems: function(itemOne, itemTwo) {
                return itemOne.sortOrder > itemTwo.sortOrder ? 1 : -1
            },

            getActiveItemIndex: function() {
                var activeIndex = 0;
                steps.sort(this.sortItems).forEach(function(element, index) {
                    if (element.isVisible()) {
                        activeIndex = index;
                    }
                });
                return activeIndex;
            },

            isProcessed: function(code) {
                var activeItemIndex = this.getActiveItemIndex();
                var sortedItems = steps.sort(this.sortItems);
                var requestedItemIndex = -1;
                sortedItems.forEach(function(element, index) {
                    if (element.code == code) {
                        requestedItemIndex = index;
                    }
                });
                return activeItemIndex > requestedItemIndex;
            },

            navigateTo: function(code, scrollToElementId) {
                var sortedItems = steps.sort(this.sortItems);
                var bodyElem = $.browser.safari || $.browser.chrome ? $("body") : $("html");
                scrollToElementId = scrollToElementId || null;

                if (!this.isProcessed(code)) {
                    return;
                }
                sortedItems.forEach(function(element) {
                    if (element.code == code) {
                        element.isVisible(true);
                        if (scrollToElementId && $('#' + scrollToElementId).length) {
                            bodyElem.animate({scrollTop: $('#' + scrollToElementId).offset().top}, 0);
                        }
                    } else {
                        element.isVisible(false);
                    }

                });
            },

            next: function() {
                var activeIndex = 0;
                steps.sort(this.sortItems).forEach(function(element, index) {
                    if (element.isVisible()) {
                        element.isVisible(false);
                        activeIndex = index;
                    }
                });
                if (steps().length > activeIndex + 1) {
                    steps()[activeIndex + 1].isVisible(true);
                    document.body.scrollTop = document.documentElement.scrollTop = 0;
                }
            },

            prev: function() {
                var activeIndex = 0;
                steps.sort(this.sortItems).forEach(function(element, index) {
                    if (element.isVisible()) {
                        element.isVisible(false);
                        activeIndex = index;
                    }
                });
                if (0 <= activeIndex - 1) {
                    steps()[activeIndex - 1].isVisible(true);
                    document.body.scrollTop = document.documentElement.scrollTop = 0;
                }
            }
        };
    }
);
