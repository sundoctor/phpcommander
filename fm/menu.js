<SCRIPT LANGUAGE="JavaScript">
<!--

var safepath = '<!--$safepath-->';
var block = false;

function openWindow(url) {
    return window.open(url, "", "");
}

function cmdRefresh() {
    document.listform.cmd.value = 'list';
    document.listform.submit();
}

function CountSelected() {
    var selected = 0;
    var filelist = document.listform.elements['file[]'];
    if (filelist!=null) {
        if (filelist.length)
            for(var i=0;i<filelist.length;i++)
                selected += filelist[i].checked?1:0;
        else
            selected = filelist.checked?1:0;
    }
    return selected;
}

function CheckOnlyOne() {
    if (CountSelected()!=1)
        return false;
    return true;
}

function cmdSelectAll() {
    var filelist = document.listform.elements['file[]'];
    if (filelist!=null) {
        if (filelist.length) {
            for(i=0;i<filelist.length;i++)
                filelist[i].checked = true;
        }
        else if (filelist.checked!=null)
            filelist.checked = true;
    }
}

function cmdSelectNone() {
    var filelist = document.listform.elements['file[]'];
    if (filelist!=null) {
        if (filelist.length) {
            for(var i=0;i<filelist.length;i++)
                filelist[i].checked = false;
        }
        else if (filelist.checked!=null)
            filelist.checked = false;
    }
}

function cmdNew() {
    if (block) return;
    var sort = document.listform.sort.value;
    block = true;
    var newurl = 'index.php?cmd=new'+
                 '&sort='+sort+'&path='+safepath;
    document.location.href = newurl;
}

function cmdView() {
    if (CountSelected()!=1) {
        alert('Select 1 file, please');
        return;
    }
    var filelist = document.listform.elements['file[]'];
    if (filelist!=null) {
        if (filelist.length) {
            for(var i=0;i<filelist.length;i++) {
                if (filelist[i].checked) {
                    var filename = ''+filelist[i].value;
                    if (filename.substr(0,1)=='F') {
                        filename = filename.substr(1);
                        var url = 'index.php?cmd=view&path=' +
                            safepath+'&filename='+filename;
                        openWindow(url);
                    }
                }
            }
        } else if (filelist.checked) {
            var filename = ''+filelist.value;
            if (filename.substr(0,1)=='F') {
                filename = filename.substr(1);
                var url = 'index.php?cmd=view&path=' +
                    safepath+'&filename='+filename;
                openWindow(url);
            }
        }
    }
}

function cmdEdit() {
    if (block) return;
    if (CountSelected()!=1) {
        alert('Select one file, please');
        return;
    }
    var fileitem = document.listform.elements['file[]'];
    var filename = '';
    if (fileitem.length) {
        for(var i=0;i<fileitem.length;i++)
            if (fileitem[i].checked) {
                filename = fileitem[i].value;
            }
    }
    else
        filename = fileitem.value;
    if (filename.substr(0,1)!='F') {
        alert('Select File!');
        return false;
    }
    block = true;
    document.listform.cmd.value = 'edit';
    document.listform.submit();
}

function cmdCut() {
    if (block) return;
    if (CountSelected()==0) {
        alert('Select files, please');
        return;
    }
    block = true;
    document.listform.cmd.value = 'cut';
    document.listform.submit();
}

function cmdCopy() {
    if (block) return;
    if (CountSelected()==0) {
        alert('Select files, please');
        return;
    }
    block = true;
    document.listform.cmd.value = 'copy';
    document.listform.submit();
}

function cmdPaste() {
    if (block) return;
    if (CountSelected()!=0) {
        alert('Uncheck all files, please');
        return;
    }
    block = true;
    document.listform.cmd.value = 'paste';
    document.listform.submit();
}

function cmdClear() {
    if (block) return;
    block = true;
    document.listform.cmd.value = 'clear';
    document.listform.submit();
}

function cmdRemove() {
    if (block) return;
    if (CountSelected()==0) {
        alert('Select files, please');
        return;
    }
    block = true;
    document.listform.cmd.value = 'remove';
    document.listform.submit();
}

function cmdRemoveAll() {
    if (block) return;
    block = true;
    document.listform.cmd.value = 'removeall';
    document.listform.submit();
}

function cmdRemoveX() {
    if (block) return;
    block = true;
    document.listform.cmd.value = 'removex';
    document.listform.submit();
}

function cmdAttr() {
    if (block) return;
    if (CountSelected()!=1) {
        alert('Select one file, please');
        return;
    }
    block = true;
    document.listform.cmd.value = 'attr';
    document.listform.submit();
}

function cmdRename() {
    if (block) return;
    if (CountSelected()!=1) {
        alert('Select one file, please');
        return;
    }
    block = true;
    document.listform.cmd.value = 'newname';
    document.listform.submit();
}

function cmdMakeDir() {
    if (block) return;
    block = true;
    document.listform.cmd.value = 'newdir';
    document.listform.submit();
}

function cmdSend() {
    if (CountSelected()!=1) {
        alert('Select one file, please');
        return;
    }
    var filelist = document.listform.elements['file[]'];
    if (filelist!=null) {
        if (filelist.length)
            for(var i=0;i<filelist.length;i++) {
                if (filelist[i].checked) {
                    var filename = filelist[i].value;
                    if (filename.substr(0,1)=='F') {
                        filename = filename.substr(1);
                        var newurl = 'index.php?cmd=download&path='+
                                     safepath+'&filename='+filename;
                        //openWindow(newurl);
                        document.location.href = newurl;
                    }
                }
            }
        else {
            if (filelist.checked) {
                var filename = filelist.value;
                if (filename.substr(0,1)=='F') {
                    filename = filename.substr(1);
                    var newurl = 'index.php?cmd=download&path='+safepath+
                                '&filename='+filename;
                    //openWindow(newurl);
                    document.location.href = newurl;
                }
            }
        }
    }
}

function goUrl(url) {
    var sort = document.listform.elements['sort'].value;
    var newurl = 'index.php?cmd=list&sort='+sort+'&path='+url;
    document.location.href = newurl;
}

//-->
</SCRIPT>
