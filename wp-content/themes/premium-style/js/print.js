function CallPrint(strid) {
	var WinPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0'); 
	WinPrint.document.write('<div style="margin-bottom: 5px; height: 22px; padding-top: 6px; padding-left: 31px; background: url(http://rostest-certify.ru/imgs/print.png) no-repeat; background-size: contain; @media print {display: none;}"><a style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size:14px; color: #666666 !important; font-weight: bold;" href="javascript://" onclick="window.print();">Печать</a></div>');
	WinPrint.document.write('<div id="print" class="contentpane">'); 
	WinPrint.document.write('<img style="width: 100%; height: auto;" src="/download/'+strid+'" title="Cертификат" alt="Cертификат"/>'); 
	WinPrint.document.write('</div>');
	WinPrint.document.close(); 
}