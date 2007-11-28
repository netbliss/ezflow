<?php
//
// Created on: <15-Feb-2007 11:25:31 bf>
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.0
// BUILD VERSION: 17785
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

class eZSquidCacheManager
{
    /*!
        Purges the given URL on the Squid server. The relative URL is passed. 
        E.g. /en/products/url_alias_for_page
    */
    static function purgeURL( $url )
    {

        $ini = eZINI::instance( 'squid.ini' );

        $server = $ini->variable( 'SquidSettings', 'Server' );
        $port = $ini->variable( 'SquidSettings', 'Port' );
        $path = $url;
        $timeout = $ini->variable( 'SquidSettings', 'Timeout' );

        $errorNumber = "";
        $errorString = "";


        $fp = fsockopen( $server,
                         $port,
                         $errorNumber,
                            $errorString,
                            $timeout );

        $HTTPRequest = "PURGE " . $path . " HTTP/1.0\r\n" .
                       "Accept: */*\r\n\r\n";

        if ( !fputs( $fp, $HTTPRequest, strlen( $HTTPRequest ) ) )
        {
            print( "Error purging cache" );
             $response = 0;
        }        

        $rawResponse = "";
        // fetch the SOAP response
        while ( $data = fread( $fp, 32768 ) )
        {
            $rawResponse .= $data;
        }

        print( $rawResponse );

        // close the socket
        fclose( $fp );
      }

}
?>