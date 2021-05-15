<?php
/**
 * Classe de acesso a dados em arquivos INI
 * autor: Henrique Mayer <hmayer@gmail.com>
 **/

 class iniParser {
    
    var $_iniFilename = '';
    var $_iniParsedArray = array();

    /** 
     *  Carrega na memória um array associativo da estrutura
     * do arquivo INI.
     **/
    function iniParser( $filename ) {
        $this->_iniFilename = $filename;
        $this->_iniParsedArray = parse_ini_file( $filename, FALSE );
    }

    /**
     *  Retorna a primeira seção do arquivo INI.
     **/
    function getMasterSection() {
        $match = '';
        $MSfdescriptor = fopen( $this->_iniFilename, "r" );
        $fdata = fread( $MSfdescriptor, filesize( $this->_iniFilename ) );
        preg_match( '/\[(.*)\]/', $fdata, $match);
        fclose( $MSfdescriptor );
        return $match[1];
    }

    /**
     *  Retorna um valor de acordo com a chave especificada
     **/
    function getValue( $key ) {
        return $this->_iniParsedArray[$key];
    }

    /**
     * Seta um valor de acordo com a chave especificada
     **/
    function setValue( $key, $value ) {
        if( $this->_iniParsedArray[$key] = $value ) return TRUE;
    }

    /**
     * Salva um arquivo .ini com os valores atuais
     **/
    function saveFile( $filename = null ) {
        if( $filename == null ) $filename = $this->_iniFilename;
        $masterSection = $this->getMasterSection();
        if( is_writeable( $filename ) ) {
            $SFfdescriptor = fopen( $filename, "w" );
            fwrite( $SFfdescriptor, "[" . $masterSection . "]\n" );
            foreach( $this->_iniParsedArray as $key => $value ) {
                fwrite( $SFfdescriptor, "$key=$value\n" );
            }
            fclose( $SFfdescriptor );
            return TRUE;
        }
    }
 }
?>
