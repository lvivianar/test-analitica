<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template name="substring-after-last">
		<xsl:param name="string" />
		<xsl:param name="delimiter" />
		<xsl:choose>
		  <xsl:when test="contains($string, $delimiter)">
			<xsl:call-template name="substring-after-last">
			  <xsl:with-param name="string"
				select="substring-after($string, $delimiter)" />
			  <xsl:with-param name="delimiter" select="$delimiter" />
			</xsl:call-template>
		  </xsl:when>
		  <xsl:otherwise><xsl:value-of select="$string" /></xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="Archivo">%|%INSERT IGNORE INTO archivos (id_archivo, nombre) VALUES ("<xsl:value-of select="@Id"/>", "<xsl:value-of select="@Nombre"/>");
		<xsl:variable name="exten">
			<xsl:call-template name="substring-after-last">
				<xsl:with-param name="string" select="@Nombre" />
				<xsl:with-param name="delimiter" select="'.'" />
			</xsl:call-template>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="contains(@Nombre, '.')">%|%INSERT IGNORE INTO archivos_info (id_archivo, extension) VALUES ("<xsl:value-of select="@Id"/>", "<xsl:value-of select="$exten"/>");
			</xsl:when>
			<xsl:otherwise>%|%INSERT IGNORE INTO archivos_info (id_archivo, extension) VALUES ("<xsl:value-of select="@Id"/>",   NULL);
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>