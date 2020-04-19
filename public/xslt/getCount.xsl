<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="/">
		<table class="table table-striped table-dark">
			<thead>
				<tr>
					<th scope="col">EXTENSION</th>
					<th scope="col">TIPO DE ARCHIVO</th>
					<th scope="col">CANTIDAD</th>
				</tr>
			</thead>
			<tbody>
			<xsl:apply-templates />
			</tbody>
		</table>
	</xsl:template>
	<xsl:template match="archivo">
		<tr>
			<td><xsl:value-of select="extension"/></td>
			<td><xsl:value-of select="tipo_archivo"/></td>
			<td><xsl:value-of select="cantidad"/></td>
		</tr>
	</xsl:template>
</xsl:stylesheet>