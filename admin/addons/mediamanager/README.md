Medienmanager
=======

Der Medienmanager verwaltet die Medien in selbsterstellen Kategorien


#### Aufruf der Klasse

```
$media = new media($id)
$media = media::getMediaByName($filename)
$media = media::getMediaByExtension($extension)
```

###### Überprüfen ob $media ein valides Media ist

```
media::isValid($media);
```

###### Weitere Methoden

```
$media->get($sqlName);
$media->getExtension();
$media->getPath();
```

### Mediamanger in Formular benutzen

```
     if(addonConfig::isActive('medienmanager')) {
       $form->addMediaField($name, $value);
       $form->addMediaListField($name, $value);
     }
```
### Medienmanager in Modulen 

##### Name 
```
DYN_MEDIA[1-10]
DYN_MEDIALIST[1-10]
```

##### Value
```
OUT_MEDIA[1-10]
OUT_MEDIALIST[1-10]
```


###### Rechte
- media[edit]
- media[delete]
- media[category][edit]
- media[category][delete]