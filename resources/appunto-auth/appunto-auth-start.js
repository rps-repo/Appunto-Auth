/*
Copyright(c) 2011 Company Name
*/
Ext.Loader.setPath("Ext","extjs/src");Ext.require("Ext.container.Viewport");Ext.require("Ext.data.TreeStore");Ext.require("Ext.form.Panel");Ext.require("Ext.form.field.Checkbox");Ext.require("Ext.form.field.ComboBox");Ext.require("Ext.grid.column.Date");Ext.require("Ext.grid.feature.Grouping");Ext.require("Ext.grid.plugin.CellEditing");Ext.require("Ext.layout.container.Border");Ext.require("Ext.layout.container.Card");Ext.require("Ext.toolbar.Paging");Ext.require("Ext.toolbar.Spacer");Ext.require("Ext.toolbar.TextItem");Ext.require("Ext.window.MessageBox");Ext.Loader.setPath("APPUNTO.lib","../appunto/lib");Ext.require("APPUNTO.lib.proxy.Codeigniter");Ext.ClassManager.setAlias("APPUNTO.lib.proxy.Codeigniter","proxy.ci");Ext.application({name:"APPA",appFolder:"app",controllers:["Main","Users","Groups","Settings","Roles","Permissions","Paths","Sessions","Info"],launch:function(){var a;if(appunto_auth_display_type==undefined||appunto_auth_display_type=="viewport"){Ext.create("APPA.view.main.Viewport").render()}else{if(appunto_auth_display_type=="container"){a=Ext.create("APPA.view.main.Container",{height:appunto_auth_height});Ext.EventManager.onWindowResize(a.doLayout,a)}else{alert("no display type defined!")}}setTimeout(function(){Ext.get("loading").remove();Ext.get("loading-mask").fadeOut({remove:true})},250)}});
