function rand_color(){
    var colors = ['#800000', '	#FF6347', '#CD5C5C','	#006400','	#20B2AA','	#6495ED','	#8A2BE2','#FF1493','#F4A460','	#00008B','	#EE82EE'];

    var arr=document.getElementsByClassName('title');
    for(i=0;i<arr.length;i++){
        var random_color = colors[Math.floor(Math.random() * colors.length)];
        arr[i].style.background = random_color; 
    }
}

                        
                    