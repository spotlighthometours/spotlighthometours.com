 <CFINVOKE component="com.pricebyzip" method="additionalproducts"  zip="#url.zip#" returnvariable="tourtype" />
<CFDUMP VAR="#tourtype#" />
<CFDUMP VAR="#StructFindValue(tourtype,"Motion Photo Tours","ALL")#" />