import './bootstrap';
import 'bootstrap';
import $ from 'jquery';
import { Chart }  from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import DataTable from 'datatables.net-bs5';

Chart.register(ChartDataLabels);
window.Chart = Chart;
window.$ = $;
window.jQuery = $;
window.DataTable = DataTable;