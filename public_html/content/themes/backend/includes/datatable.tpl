<style>
    .panel-body {
        padding: 15px;
    }
    
    .btn-group .btn:not(.dropdown-toggle) {
        border-radius: 0;
        border-radius: 0;
    }
    
    .sidebar-menu>li>a,
    .sidebar-menu>li>a {
        transition: all 0.5s
    }
    
    .sidebar-menu>li:hover>a,
    .sidebar-menu>li.active>a {
        color: #ffffff;
        background: #1b1b1b;
        border-left-color: #00b3df;
    }
    
    .box {
        position: relative;
        border-radius: 2px;
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-top: 3px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }
    
    @media (min-width: 768px) {
        .modal-sm {
            width: 330px;
        }
    }
</style>

<link rel="stylesheet" type="text/css" href="/content/themes/backend/css/datatable.css">

<div ng-controller="data as showCase">
    <div class="box box-default">
        {include file="includes/datatable.header.tpl"}
        <div class="box-body">
            <table datatable="" id="dt_data" dt-options="showCase.dtOptions" dt-column-defs="showCase.dtColumnDefs" class="display data-table compact" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        {if isset($table.columns) && count($table.columns)} {foreach $table.columns as $key => $value}
                        <th>{$key}</th>
                        {/foreach} {/if}
                        <th>{_("Acciones")}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        {if isset($table.columns) && count($table.columns)} {foreach $table.columns as $key => $value}
                        <th>{$key}</th>
                        {/foreach} {/if}
                        <th>{_("Acciones")}</th>
                    </tr>
                </tfoot>

                <tbody>

                    {if isset($data) && count($data)} {foreach $data as $key => $value} {if $value.status >= 0}
                    <tr id="tr_{$value.id}">
                        {if isset($table.columns) && count($table.columns)} {foreach $table.columns as $ckey => $cvalue}

                        <td align="{$cvalue['align']}">
                            {*Si la columna es de tipo "date"*} {if isset($cvalue['type']) AND $cvalue['type'] == 'date'} {$value[$cvalue['field']]|date_format:$cvalue['format']} {*Si la columna es de tipo "number"*} {elseif isset($cvalue['type']) AND $cvalue['type'] == 'number'}
                            {* 'format' => array( int Dcimanles, string Separador de deciamles, string Separador de Miles ) *} {number_format($value[$cvalue['field']], $cvalue['format'][0], $cvalue['format'][1], $cvalue['format'][2])} {*Si la columna
                            es de tipo "label"*} {elseif isset($cvalue['type']) AND $cvalue['type'] == 'label'}
                            <span class="label label-{$value[$cvalue['class']]|default:'default'}" {if $cvalue[ 'tooltips']}{*html::tooltips($value[$cvalue[ 'tooltips']])*}{/if}>{$value[$cvalue['field']]}</span> {else} {*Si no existe un tipo establecido,
                            pero se tiene un formato*} {if isset($cvalue['format']) AND $cvalue['format']} {$value[$cvalue['field']]|string_format:$cvalue['format']} {else} {*Si no existe un tipo establecido, ni formato*} {if isset($cvalue['primary'])
                            AND $cvalue['primary'] == TRUE}
                            <a href="javascript:viewrecord_handler({$value.id})">{$value[$cvalue['field']]}</a> {else} {$value[$cvalue['field']]} {/if} {/if} {/if}

                        </td>
                        {/foreach} {/if}

                        <td align="center">
                            {if isset($action_datatable_before) && count($action_datatable_before)} {foreach $action_datatable_before as $k => $v}
                            <button class="btn btn-{$v['color']|default:'default'}" type="button" data-id="{$value.id}" onclick="javascript:{$v['onclick']}" {if $v[ 'tooltip']}{*html::tooltips( "{$v['tooltip']}")*}{/if}><span class="fa fa-{$v['icon']|default:'circle'}"></span></button>                            {/foreach} {/if} {if $table.view}<button class="btn btn-default" onclick="javascript:viewrecord_handler({$value.id})" title="" type="button" {*html::tooltips( "Visualizar los detalles de este registro")*}><span class="fa fa-eye"></span> </button>{/if}
                            {if $table.edit}<button class="btn btn-info" onclick="javascript:editrecord_handler({$value.id})" title="" type="button" {*html::tooltips( "Haga click para editar este registro")*}><span class="fa fa-edit"></span> </button>{/if}
                            {if $table.delete}<button class="btn btn-danger" onclick="javascript:deleterecord_handler({$value.id})" title="" type="button" {*html::tooltips( "Haga click para eliminar este registro")*}><span class="fa fa-trash"></span> </button>{/if}
                            {if isset($action_datatable_after) && count($action_datatable_after)} {foreach $action_datatable_before as $k => $v}
                            <button class="btn btn-{$v['color']|default:'default'}" type="button" data-id="{$value.id}" onclick="javascript:{$v['onclick']}" {if $v[ 'tooltip']}{*html::tooltips( "{$v['tooltip']}")*}{/if}><span class="fa fa-{$v['icon']|default:'circle'}"></span></button>                            {/foreach} {/if}
                        </td>
                    </tr>
                    {/if} {/foreach} {/if}
                </tbody>
            </table>
            {if isset($paginator)}{$paginator}{/if}
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

<script>
    var _dt_data;
    var _option_dt_data = {
        "language": {
            "info": "Registros <strong>_START_</strong> al <strong>_END_</strong> de un total de <strong>_TOTAL_</strong> registros",
            "infoFiltered": " - filtrado de _MAX_ registros",
            "processing": "Procesando...",
            "search": "Filtrar: ",
            "sEmptyTable": "Sin datos para mostrar...",
            "sLoadingRecords": "Cargando...",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sZeroRecords": "No se encontraron resultados",
            "sSearchPlaceholder": "Filtar resultados...",
            "sDecimal": ",",
            "sInfoThousands": ",",
            "searchDelay": 100,
            "lengthMenu": 'Mostrar <select class="form-control" style="display: inline-block;width: auto;">' +
                '<option value="10">10</option>' +
                '<option value="25">25</option>' +
                '<option value="50">50</option>' +
                '<option value="-1">Todos</option>' +
                '</select> páginas',
            "paginate": {
                "sFirst": '<span class="fa fa-square-o-left"></span>',
                "sLast": '<span class="fa fa-square-o-right"></span>',
                "sNext": '<span class="fa fa-caret-right"></span>',
                "sPrevious": '<span class="fa fa-caret-left"></span>'
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    }; {
        if $dt_notsearching == true
    }
    _option_dt_data.searching = false; {
        /if} {
            if $dt_notpaginate == true
        }
        _option_dt_data.paginate = false;
        _option_dt_data.info = false; {
            /if}

            $(document).ready(function() {
                _dt_data = $('#dt_data').DataTable(_option_dt_data);
            })
</script>