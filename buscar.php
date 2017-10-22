<?php

$html = 
<<<HTML
<div id="searchin_main">
    <fieldset id="searchin_fieldset">
        <legend>Busca R&aacutepida</legend>
        <form action="buscar.php" method="post">
            <label for="id_type_name">Pesquisar por nome: </label>
            <input type="radio" name="type" value="name" id="id_type_name" checked />
            <br />
            <label for="id_type_internal">Pesquisar dentro dos arquivos: </label>
            <input type="radio" name="type" value="internal" id="id_type_internal" />
            <br />
            <label for="id_str_search">Palavra a ser pesquisada: </label>
            <input type="text" name="str_search" value="" id="id_str_search" />
            <br />
            <p><input type="submit" value="Buscar" /></p>
        </form>   
    </fieldset> asdsdads
</div>
HTML;

echo $html;

if( isset( $_POST['str_search'] ) && isset( $_POST['str_search']{2} ) )
{
    
    // Filtra a string para remover caracteres especiais    
    $str_search = filter_input( INPUT_POST, 'str_search', FILTER_SANITIZE_SPECIAL_CHARS );
    $option = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS );
    
    // Tipos de arquivos permitidos para busca    
    $file_types = array( 'html', 'htm', 'tpl', 'ini', 'conf', 'css', 'js', 'php' );

    // Diretório atual     
    $directory = getcwd(); 
    
    echo '<h1>Buscando por: ', $str_search, '</h1>';

    function search_in( $dir, $option, $str_search, $file_types ){
             
        //echo '<h3>Entrou em: ', $dir, '</h3>';

        // Abrir o diretório
        $op_dir = opendir( $dir );
       
        // Varre todos os itens encontrados
        while ( $item = readdir( $op_dir ) )
        {
                    
            if( $item{0} != '.' && $item{0} != '_' )
            {
         
                if( is_dir( $dir .'/'. $item ) )
                {
                    search_in( $dir .'/'. $item, $option, $str_search, $file_types );    
                }
                else
                {

                    $ext = pathinfo( $dir .'/'. $item, PATHINFO_EXTENSION );

                    if( array_search( $ext, $file_types ) !== false )
                    {
                    
                        if( $option == 'name' )
                        {
                            
                            if( stripos( $item, $str_search ) !== false )
                            {

                                echo '<h3>Encontrado!</h3>',
                                '<b>Local:</b> ', $dir, '/', $item, '<br />',
                                '<hr />';
                                                                                                                 
                            }

                        }
                        elseif( $option == 'internal' )
                        {

                            $files = file( $dir .'/'. $item );
                            
                            foreach( $files as $row_number => $row )
                            {                

                                if( stripos( $row, $str_search ) !== false )
                                {
                                    
                                    echo '<h3>Encontrado!</h3>',
                                         '<b>Local:</b> ', $dir, '/', $item, '<br />',
                                         '<b>Linha:</b> ', $row_number, '<br />',
                                         '<b>Trecho:</b> ', str_replace( $str_search, '<b>'. $str_search .'</b>', htmlspecialchars( $row ) ), '<hr />';
                                                                                                                     
                                }

                            }             
                            
                        }

                    }

                }
            
            }

        }
        
        closedir( $op_dir );
                    
    }

    search_in( $directory, $option, $str_search, $file_types );
    
    echo '<h3>Fim!</h3>';

}
