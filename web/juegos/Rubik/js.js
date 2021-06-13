var app, menu, settings, camera, controls, rubik, utils, pointHelper, vertexHelper, planeHelper;

settings = {
	allowInput:     true,
	cubesPerSide:     3,
	cubeSize:       100,
	rubikRotPerWin: 360, // degrees
	rubikRotSpeed:    5, // filled by app
	randomizeFactor: 40,
	rotationTicks:   20,
	rubik: {
		colors: {
			red:    '#b71234',
			orange: '#ff5800',
			yellow: '#ffd500',
			green:  '#009b48',
			blue:   '#0046ad',
			white:  '#ffffff',
			black:  '#000000'
		},

		rotPerWindowWidth: 360
	},
	arrow: {
		color:       'rgba(255,0,255,{alpha})',
		width:       100,
		height:      100,
		scaleDiff:   0.01,
		opacityDiff: 0.015,
		maxScale:    1.35,
		minScale:    1,
		hideTime:    100,
		animationCount: 2,
	},

	screen: { /* filled by app */ },
}

function Vertex(x, y, z, unit) {

	this.x    = parseFloat(x) || 0;
	this.y    = parseFloat(y) || 0;
	this.z    = parseFloat(z) || 0;
	this.unit = unit || Vertex.units.length;
}

Vertex.prototype = {

};

Vertex.units = {
	length:  0,
	degrees: 1,
	radian:  2
};

function Point(x, y) {

	this.x = parseFloat(x) || 0;
	this.y = parseFloat(y) || 0;
}

utils = {
	generateRandomId: function (length) { 
        return Math.random().toString(36).substr(2, length || 5); 
    }
};

pointHelper = {
	round: function (v, places) {

		if (typeof places == 'number' && places > 0) { 
			return new Point(
				v.x.toFixed(places),
				v.y.toFixed(places)
			);
		}

		return new Point(
			Math.round(v.x),
			Math.round(v.y)
		);
	}
};

vertexHelper = {
	rotate: function (v, r) {
		r = vertexHelper.toRadian(r);

		if (r.y) { v = vertexHelper.rotateY(v, r.y); }
		if (r.z) { v = vertexHelper.rotateZ(v, r.z); }
		if (r.x) { v = vertexHelper.rotateX(v, r.x); }

		return v;
	},

	rotateX: function (v, ang) {
		if (!ang) { return v; }

		return new Vertex(
			v.x,
			v.y * Math.cos(ang) - v.z * Math.sin(ang),
			v.y * Math.sin(ang) + v.z * Math.cos(ang)
		);
	},

	rotateY: function (v, ang) {
		if (!ang) { return v; }

		return new Vertex(
			 v.x * Math.cos(ang) + v.z * Math.sin(ang),
			 v.y,
			-v.x * Math.sin(ang) + v.z * Math.cos(ang)
		);
	},

	rotateZ: function (v, ang) {
		if (!ang) { return v; }

		return new Vertex(
			v.x * Math.cos(ang) - v.y * Math.sin(ang),
			v.x * Math.sin(ang) + v.y * Math.cos(ang),
			v.z
		);
	},

	normalize: function (v) {
		var magnitude = Math.sqrt(v.x * v.x) + Math.sqrt(v.y * v.y) + Math.sqrt(v.z * v.z);

		if (magnitude == 0) { return v; }

		return new Vertex(
			v.x / magnitude,
			v.y / magnitude,
			v.z / magnitude
		);
	},

	clip: function (v) {
		var max;

		switch (v.unit) {
			case Vertex.units.radian:  max = Math.PI * 2; break;
			case Vertex.units.degrees: max = 360;         break;
			default: return v;
		}

		while (v.x >= max) { v.x -= max; }
		while (v.x <  0)   { v.x += max; }
		while (v.y >= max) { v.y -= max; }
		while (v.y <  0)   { v.y += max; }
		while (v.z >= max) { v.z -= max; }
		while (v.z <  0)   { v.z += max; }

		return v;
	},

	toRadian: function (v) {
		if (v.unit == Vertex.units.radian) { return v; }

		return new Vertex(
			v.x * Math.PI / 180,
			v.y * Math.PI / 180,
			v.z * Math.PI / 180,
			Vertex.units.radian
		);
	},

	toDegrees: function (v) {
		if (v.unit == Vertex.units.degrees) { return v; }

		return new Vertex(
			(180 * v.x / Math.PI).toFixed(2),
			(180 * v.y / Math.PI).toFixed(2),
			(180 * v.z / Math.PI).toFixed(2),
			Vertex.units.degrees
		);
	},

	changeUnits: function (v, newUnit) {
		if (newUnit == v.unit) { return v; }

		switch (newUnit) {
			case Vertex.units.radian: return vertexHelper.toRadian (v);
			default: 			      return vertexHelper.toDegrees(v);
		}
	},

	round: function (v, places) {

		if (typeof places == 'number' && places > 0) { 
			return new Vertex(
				v.x.toFixed(places),
				v.y.toFixed(places),
				v.z.toFixed(places)
			);
		}

		return new Vertex(
			Math.round(v.x),
			Math.round(v.y),
			Math.round(v.z)
		);
	},

	multiply: function (v1, v2) {
		
		return typeof v2 == 'number'
		   ? new Vertex(v1.x * v2,   v1.y * v2,   v1.z * v2)
		   : new Vertex(v1.x * v2.x, v1.y * v2.y, v1.z * v2.z);
	},

	divide: function (v1, v2) {

		return typeof v2 == 'number'
		   ? new Vertex(v1.x / v2,   v1.y / v2,   v1.z / v2)
		   : new Vertex(v1.x / v2.x, v1.y / v2.y, v1.z / v2.z);
	},

	add: function (v1, v2, clip) {

		return typeof v2 == 'number'
		   ? new Vertex(v1.x + v2,   v1.y + v2,   v1.z + v2)
		   : new Vertex(v1.x + v2.x, v1.y + v2.y, v1.z + v2.z);
	},

	subtract: function (v1, v2, clip) {

		return typeof v2 == 'number'
		   ? new Vertex(v1.x - v2,   v1.y - v2,   v1.z - v2)
		   : new Vertex(v1.x - v2.x, v1.y - v2.y, v1.z - v2.z);
	},

	cross: function (a, b) {
		return new Vertex(
			a.y * b.z - a.z * b.y,
			a.z * b.x - a.x * b.z,
			a.x * b.y - a.y * b.x
		);
	},

	dot: function (a, b) {
		return a.x * b.x + a.y * b.y + a.z * b.z;
	},

	to2D: function (v) {

		var scale = (camera.distance - v.z + camera.position.z) / camera.distance;

		return new Point(
			 v.x * scale + settings.screen.center.x,
			-v.y * scale + settings.screen.center.y
		);
	},

	distance: function (v1, v2) {
		var x = v1.x - v2.x;
		var y = v1.y - v2.y;
		var z = v1.z - v2.z;

		return Math.sqrt(x * x + y * y + z * z);
	},

	toGlobalCoord: function (v, r, parent) {

		if (r) { v = vertexHelper.rotate(v, r); }

		while (parent) {
			v = vertexHelper.add   (v, parent.position);
			v = vertexHelper.rotate(v, parent.rotation);

			parent = parent.parent;
		}

		return v;
	}
};

planeHelper = {
	containsPoint: function (points, p) {
		var xArr, yArr, minX, minY, maxX, maxY, i, j, inside;

		xArr = points.map(function (v) { return v.x; });
		yArr = points.map(function (v) { return v.y; });
		minX = Math.min.apply(null, xArr);
		minY = Math.min.apply(null, yArr);
		maxX = Math.max.apply(null, xArr);
		maxY = Math.max.apply(null, yArr);

		if (p.x < minX ||
			p.x > maxX ||
			p.y < minY ||
			p.y > maxY) { return false; }

		for (i = 0, j = points.length - 1; i < points.length; j = i++) {
			if ((yArr[i] > p.y) != (yArr[j] > p.y) && p.x < (xArr[j] - xArr[i]) * (p.y - yArr[i]) / (yArr[j] - yArr[i]) + xArr[i]) {
				inside = !inside;
			}
		}

		return inside;
	},

	getVertexOnPlane: function (vArr, target) {
		
		var normal   =   planeHelper.findNormal(vArr);
		var subtract =  vertexHelper.subtract(target, vArr[0]);
		var distance = -vertexHelper.dot(normal, subtract);

		return vertexHelper.add(
			target,
			vertexHelper.multiply(normal, distance)
		);
	},

	getVertexZCoordOnPlane: function (vArr, target) {
		var normal = planeHelper.findNormal(vArr);

		if (normal.z == 0) { return vArr[0].z; }

		var x = normal.x * (target.x - vArr[0].x);
		var y = normal.y * (target.y - vArr[0].y);

		return (x + y) / -normal.z + vArr[0].z;
	},

	findNormal: function (vertices) {
		if (!(vertices instanceof Array) || vertices.length < 3) { return new Vertex(); }

		var ab, ac, result;

		ab = vertexHelper.subtract(vertices[0], vertices[1]);
		ac = vertexHelper.subtract(vertices[0], vertices[2]);

		result = vertexHelper.cross(ab, ac);
		result = vertexHelper.normalize(result);

		return result;
	},

	isClockwise: function (vertices, referencePoint) {
		return vertexHelper.dot(planeHelper.findNormal(vertices), referencePoint || camera.normal) < 0;
	},

	findCenter: function (vertices) {
		var center = new Vertex();

		vertices.forEach(function(v) { 
			center.x += v.x;
			center.y += v.y;
			center.z += v.z;
		});

		center.x /= vertices.length;
		center.y /= vertices.length;
		center.z /= vertices.length;

		return center;
	}
};

function Rubik() {

	this.id = utils.generateRandomId();
	this.initialize();
}

Rubik.prototype = {
	initialize: function () {
		var x, y, z, cube, index, min, max, faces;

		min = -(settings.cubesPerSide - 1) * settings.cubeSize / 2;

		this.faces     = [];
		this.cubes     = [];
		this.modifiers = [];
		this.position  = new Vertex();
		this.rotation  = new Vertex(-20, -45, 0, Vertex.units.degrees);

		for (z = 0; z < settings.cubesPerSide; z++)
		for (y = 0; y < settings.cubesPerSide; y++)
		for (x = 0; x < settings.cubesPerSide; x++) { 
			cube = new Cube(
				new Vertex(x,y,z), 
				new Vertex(
					min + x * settings.cubeSize,
					min + y * settings.cubeSize,
					min + z * settings.cubeSize
				),
				this
			);

			this.cubes.push(cube);
			this.faces.push.apply(this.faces, Cube.generateFaces(cube));

		}
	},

	isSolved: function () {
		var colors, i, face, dirs;

		colors = {};
		dirs   = Face.direction;

		for (i = 0; i < this.faces.length; i++) {

			face = this.faces[i];

			if (face.isOutsideFacing) {
				if (!colors[face.direction]) { colors[face.direction] = face.color; }
				else if (colors[face.direction] != face.color) { return false; }
			}
		}
		
		return true;
	},

	rotate: function (xDiff, yDiff, zDiff) {
		this.rotation = vertexHelper.clip(
			vertexHelper.add(
				this.rotation, 
				new Vertex(xDiff, yDiff, zDiff)
			)
		);
	},

	onResize: function () {
		var i;

		for (i = 0; i < this.cubes.length; i++) {
			if (typeof this.cubes[i].onResize == 'function') {
				this.cubes[i].onResize();
			}
		}

		for (i = 0; i < this.faces.length; i++) {
			if (typeof this.faces[i].onResize == 'function') {
				this.faces[i].onResize();
			}
		}
	},

	update: function () {
		var i, diff;

		for (i = 0; i < this.modifiers.length; i++) {
			if (this.modifiers[i].update() == false || !this.modifiers[i].isActive) {
				this.modifiers.splice(i--, 1);
			}
		}

		for (i = 0; i < this.faces.length; i++) {
			this.faces[i].update();
		}

		this.faces = this.faces.sort(function (a, b) {
			
			if (a.isOutsideFacing != b.isOutsideFacing) {
				return (a.isOutsideFacing ? -1 : 1) 
				     - (b.isOutsideFacing ? -1 : 1);
			}

			if (b.minDistance > a.maxDistance || 
				a.maxDistance < b.minDistance ||
				b.parent.id == a.parent.id) {
				return a.minDistance - b.minDistance;
			}

			for (i = 0; i < a.outerPoints.length; i++) {
				if (planeHelper.containsPoint(b.outerPoints, a.outerPoints[i])) {

					// if the vertex that lies on the plane is closer than "a.outerGlobalVertices[i].z" then the plane
					// is closer to the camera and a negative value should be returned. i.e. b is closer than a.
					diff = a.outerGlobalVertices[i].z - planeHelper.getVertexOnPlane(b.outerGlobalVertices, a.outerGlobalVertices[i]).z;
					if (diff != 0) { return diff; }
				}
			}

			for (i = 0; i < b.outerPoints.length; i++) {
				if (planeHelper.containsPoint(a.outerPoints, b.outerPoints[i])) {
					diff = planeHelper.getVertexOnPlane(a.outerGlobalVertices, b.outerGlobalVertices[i]).z - b.outerGlobalVertices[i].z;
					if (diff != 0) { return diff; }
				}
			}

			return a.minDistance - b.minDistance;
		});
	},

	render: function (scene) {
		for (var i = this.faces.length - 1; i > -1; i--) {
			this.faces[i].render(scene);
		}
	},

	dispose: function () {
		// TODO:
	}
};

function Cube(index, position, parent) {

	this.id  	  = utils.generateRandomId();
	this.index    = index;
	this.position = position;
	this.parent   = parent;

	this.initialize();
}

Cube.prototype = {
	initialize: function () {

		this.rotation = new Vertex(0, 0, 0, Vertex.units.degrees);
		this.distance = Infinity;

		if (!this.position) { this.position = new Vertex(); }
	},

	rotate: function (xDiff, yDiff, zDiff) {
		this.rotation = vertexHelper.clip(
			vertexHelper.add(
				this.rotation, 
				new Vertex(xDiff, yDiff, zDiff)
			)
		);
	},

	onResize: function () {

		this.position = vertexHelper.add(
			vertexHelper.multiply(this.index, settings.cubeSize),
			-(settings.cubesPerSide - 1) * settings.cubeSize / 2
		);
	},

	reorient: function () {
		var newIndex;

		this.position = vertexHelper.rotate(this.position, this.rotation);
		this.rotation = new Vertex(0, 0, 0, Vertex.units.degrees);

		newIndex = vertexHelper.divide(this.position, settings.cubeSize);
		newIndex = vertexHelper.add   (newIndex, (settings.cubesPerSide - 1) / 2);
		newIndex = vertexHelper.round (newIndex);

		this.index = newIndex;
	}
};

Cube.generateFaces = function (cube) {
	return [
		new Face(Face.direction.front,  cube, settings.rubik.colors.orange, cube.index.z == 0),
		new Face(Face.direction.back,   cube, settings.rubik.colors.red,    cube.index.z == settings.cubesPerSide - 1),
		new Face(Face.direction.left,   cube, settings.rubik.colors.yellow, cube.index.x == 0),
		new Face(Face.direction.right,  cube, settings.rubik.colors.white,  cube.index.x == settings.cubesPerSide - 1),
		new Face(Face.direction.bottom, cube, settings.rubik.colors.blue,   cube.index.y == 0),
		new Face(Face.direction.top,    cube, settings.rubik.colors.green,  cube.index.y == settings.cubesPerSide - 1)
	];
};


function Face(direction, parent, color, isOutsideFacing) {

	this.direction       = direction;
	this.parent          = parent;
	this.color 			 = color;
	this.isOutsideFacing = isOutsideFacing;

	this.initialize();
}

Face.prototype = {
	initialize: function () {
		var min, max, innerOffset, outerOffset, diff, vo, vi, i, vertices;

		innerOffset = 0.05;
		outerOffset = settings.cubeSize / 2; 
		vertices    = [
			new Vertex(-1,  1, -1), // left  top    front
			new Vertex( 1,  1, -1), // right top    front
			new Vertex( 1, -1, -1), // right bottom front
			new Vertex(-1, -1, -1)  // left  bottom front
		];

		this.innerVertices       = [];
		this.outerVertices       = [];
		this.outerGlobalVertices = [];
		this.innerGlobalVertices = [];
		this.outerPoints 		 = [];
		this.innerPoints 		 = [];
		this.rotation            = Face.getRotation(this.direction);

		for (i = 0; i < vertices.length; i++) { 
			this.outerVertices.push(vertexHelper.multiply(vertices[i], outerOffset)); 
		}

		if (this.isOutsideFacing) {
			min = new Vertex(
				Math.min.apply(null, this.outerVertices.map(function (v) { return v.x })),
				Math.min.apply(null, this.outerVertices.map(function (v) { return v.y })),
				Math.min.apply(null, this.outerVertices.map(function (v) { return v.z }))
			);

			max = new Vertex(
				Math.max.apply(null, this.outerVertices.map(function (v) { return v.x })),
				Math.max.apply(null, this.outerVertices.map(function (v) { return v.y })),
				Math.max.apply(null, this.outerVertices.map(function (v) { return v.z }))
			);

			diff = new Vertex(
				(max.x - min.x) * innerOffset,
				(max.y - min.y) * innerOffset,
				(max.z - min.z) * innerOffset
			);

			for (i = 0; i < this.outerVertices.length; i++) {
				vo = this.outerVertices[i];

				this.innerVertices.push(
					new Vertex(
						vo.x + (vo.x == min.x ? diff.x : -diff.x),
						vo.y + (vo.y == min.y ? diff.y : -diff.y),
						vo.z + (vo.z == min.z ? diff.z : -diff.z)
					)
				);
			}
		}
	},

	onResize: function () {
		this.initialize();
	},

	update: function () {

		var distance, maxDistance, minDistance;

		maxDistance = 0;
		minDistance = Infinity;
		
		this.minDistance = Infinity;
		this.maxDistance = Infinity;

		for (var i = 0; i < this.outerVertices.length; i++) {
			this.outerGlobalVertices[i] = vertexHelper.toGlobalCoord(this.outerVertices[i], this.rotation, this.parent);
		    this.        outerPoints[i] = vertexHelper.to2D(this.outerGlobalVertices[i]);

			if (this.isOutsideFacing) {
				this.innerGlobalVertices[i] = vertexHelper.toGlobalCoord(this.innerVertices[i], this.rotation, this.parent);
				this.        innerPoints[i] = vertexHelper.to2D(this.innerGlobalVertices[i]);
			}

			distance = vertexHelper.distance(this.outerGlobalVertices[i], camera.position);

			if (distance > maxDistance) { maxDistance = distance; }
			if (distance < minDistance) { minDistance = distance; }
		}

		this.isClockwise = planeHelper.isClockwise(this.outerGlobalVertices, camera.normal);

		if (this.isClockwise) {
			
			this.minDistance = minDistance;
			this.maxDistance = maxDistance;
		}
	},

	render: function (scene) {
		if (!this.isClockwise) { return; }

		var i;

		scene.fillStyle = settings.rubik.colors.black; 

		scene.beginPath();
		scene.moveTo(this.outerPoints[0].x, this.outerPoints[0].y);

		for (i = 0; i < this.outerPoints.length; i++) {
			scene.lineTo(this.outerPoints[i].x, this.outerPoints[i].y);
		}

		scene.fill();

		if (this.isOutsideFacing) {

			scene.fillStyle = this.active ? '#ff00ff' : this.color;

			scene.beginPath();
			scene.moveTo(this.innerPoints[0].x, this.innerPoints[0].y);

			for (i = 0; i < this.innerPoints.length; i++) {
				scene.lineTo(this.innerPoints[i].x, this.innerPoints[i].y);
			}

			scene.fill();
		}
	}
};

Face.getRotation = function (direction) {

	switch (direction) {
		case Face.direction.back:   return new Vertex(180,   0, 0, Vertex.units.degrees);
		case Face.direction.top:    return new Vertex( 90,   0, 0, Vertex.units.degrees); 
		case Face.direction.bottom: return new Vertex(270,   0, 0, Vertex.units.degrees);
		case Face.direction.left:   return new Vertex(  0,  90, 0, Vertex.units.degrees);
		case Face.direction.right:  return new Vertex(  0, 270, 0, Vertex.units.degrees);
		default:                    return new Vertex(  0,   0, 0, Vertex.units.degrees);
	}
};

Face.reorient = function (face) {
	var dirIndex, dirs, diff;

	dirs = [];

	if (face.parent.rotation.x) {

		dirs = [
			Face.direction.front,
			Face.direction.top,
			Face.direction.back,
			Face.direction.bottom
		];

		diff = Math.round(face.parent.rotation.x / 90);
	}
	else if (face.parent.rotation.y) {

		dirs = [
			Face.direction.left,
			Face.direction.back,
			Face.direction.right,
			Face.direction.front
		];

		diff = Math.round(face.parent.rotation.y / 90);
	}
	else if (face.parent.rotation.z) {

		dirs = [
			Face.direction.bottom,
			Face.direction.right,
			Face.direction.top,
			Face.direction.left
		];

		diff = Math.round(face.parent.rotation.z / 90);
	}

	dirIndex = dirs.indexOf(face.direction);

	if (dirIndex > -1) {

		dirIndex = dirIndex + diff;

		while (dirIndex < 0)               { dirIndex += dirs.length; }
		while (dirIndex > dirs.length - 1) { dirIndex -= dirs.length; }

		face.direction = dirs[dirIndex];
		face.initialize();
	}
};

Face.direction = {
	front:  'front',
	back:   'back',
	left:   'left',
	right:  'right',
	top:    'top',
	bottom: 'bottom'
};

Face.planes = {
	above: [
		new Vertex(0, 5, 0), 
		new Vertex(0, 5, 0),
		new Vertex(0, 1, 0),
		new Vertex(0, 1, 0)
	],

	below: [
		new Vertex(0, -1, 0), 
		new Vertex(0, -1, 0),
		new Vertex(0, -5, 0),
		new Vertex(0, -5, 0)
	],

	left: [ 
		new Vertex(-5, 0, 0), 
		new Vertex(-1, 0, 0),
		new Vertex(-1, 0, 0),
		new Vertex(-5, 0, 0)
	],

	right: [
		new Vertex(1, 0, 0), 
		new Vertex(5, 0, 0),
		new Vertex(5, 0, 0),
		new Vertex(1, 0, 0)
	]
};

function Plane(vertices, rotVertex, color) {

	this.id  		    = utils.generateRandomId();
	this.points         = vertices.map(function (v) { return vertexHelper.to2D(v); });
	this.targetRotation = rotVertex;
	this.color          = color;
}

Plane.prototype = {
	render: function (scene) {
		scene.fillStyle = this.color; 

		scene.beginPath();
		scene.moveTo(this.points[0].x, this.points[0].y);

		for (i = 0; i < this.points.length; i++) {
			scene.lineTo(this.points[i].x, this.points[i].y);
		}

		scene.fill();
	}
};

function Arrow(originVertex, direction, rotation, parent, shouldRender) {

	this.id  		  = utils.generateRandomId();
	this.originVertex = originVertex || new Vertex();
	this.direction    = direction    || new Vertex(0,0,0,Vertex.units.degrees);
	this.rotation     = rotation     || new Vertex();
	this.parent       = parent;
	this.shouldRender = shouldRender;

	this.initialize();
}

Arrow.prototype = {
	initialize: function () {
		var size = new Vertex(settings.arrow.width, settings.arrow.height);

		this.scale          = 1;
		this.opacity 	    = 0;
		this.scaleDiff 		= settings.arrow.scaleDiff;
		this.animationState = Arrow.animationStates.fadeIn;
		this.animationCount = 0;
		this.vertices = Arrow.vertices.map(function (v) {
			v = vertexHelper.multiply(v, size);
			return v;
		});
	},

	hide: function () {
		this.shouldRender = false;
	},

	resetAnimation: function () {
		this.shouldRender   = true;
		this.scale          = 1;
		this.scaleDiff      = settings.arrow.scaleDiff;
		this.opacity 	    = 0;
		this.animationState = Arrow.animationStates.fadeIn;
		this.animationCount = 0;
	},

	update: function () {
		var scale     = this.scale;
		var origin    = this.originVertex;
		var rotation  = this.rotation;
		var direction = this.direction;
		var parent    = this.parent;

		switch (this.animationState) {
			case Arrow.animationStates.hidden:
				if (++this.animationCount >= settings.arrow.hideTime) {
					this.animationState = Arrow.animationStates.fadeIn;
					this.animationCount = 0;
				}
				break;

			case Arrow.animationStates.fadeOut:
				this.scale += settings.arrow.scaleDiff;
				
				if ((this.opacity -= settings.arrow.opacityDiff) <= 0) {
					this.scale = 1;

					if (++this.animationCount >= settings.arrow.animationCount) {
						this.animationState = Arrow.animationStates.hidden;
					}
					else {
						this.animationState = Arrow.animationStates.fadeIn;
					}
				}
				break;
			
			case Arrow.animationStates.scaling:
				this.scale += settings.arrow.scaleDiff;

				if (this.scale > settings.arrow.maxScale) { 
					this.animationState = Arrow.animationStates.fadeOut;
				}
				break;

			case Arrow.animationStates.fadeIn:
				this.scale += settings.arrow.scaleDiff;

				if ((this.opacity += 2 * settings.arrow.opacityDiff) >= 1) {
					this.animationState = Arrow.animationStates.scaling;
				}
				break;
		}

		this.color = settings.arrow.color.split('{alpha}').join(this.opacity);

		this.globalVertices = this.vertices.map(function (v) {
			v = vertexHelper.add          (v, new Vertex(settings.cubeSize * scale, 0,0));
			v = vertexHelper.rotate       (v, direction);
			v = vertexHelper.add          (v, origin);
			v = vertexHelper.toGlobalCoord(v, rotation, parent);

			return v;
		});

		this.points = this.globalVertices.map(function (v) {
			return vertexHelper.to2D(v);
		});
	},

	render: function (scene) {
		if (!this.shouldRender || this.opacity <= 0) { return false; }

		scene.fillStyle = this.color;

		scene.beginPath();
		scene.moveTo(this.points[0].x, this.points[0].y);

		for (i = 0; i < this.points.length; i++) {
			scene.lineTo(this.points[i].x, this.points[i].y);
		}

		scene.fill();
	}
};

Arrow.animationStates = {
	fadeIn: 0,
	scaling: 1,
	fadeOut: 2,
	hidden:  3
};

Arrow.vertices = [
	new Vertex(0,    0.15, 0),
	new Vertex(0.6,  0.15, 0),
	new Vertex(0.6,  0.40, 0),
	new Vertex(1.0,  0,    0),
	new Vertex(0.6, -0.40, 0),
	new Vertex(0.6, -0.15, 0),
	new Vertex(0,   -0.15, 0)
];

function CubeRotationModifier(cubes, rotAmount) {

	this.cubes      = cubes;
	this.rotation   = rotAmount;
	this.ticks      = 0; //console.log(settings.rotationTicks);
	this.totalTicks = settings.rotationTicks;
	this.isActive   = true;
	this.dRotation  = vertexHelper.multiply(rotAmount, 1/this.totalTicks);
}

CubeRotationModifier.prototype = {
	update: function () {
		if (++this.ticks <= this.totalTicks) { 

			var i;

			for (i = 0; i < this.cubes.length; i++) {
				this.cubes[i].rotate(this.dRotation.x, this.dRotation.y, this.dRotation.z);
			}

			if (this.ticks >= this.totalTicks) { 

				var ids = this.cubes.map(function (c) { return c.id; });

				// updating faces
				rubik.faces.forEach(function (face) {
					if (ids.includes(face.parent.id)) {
						Face.reorient(face);
					}
				});

				// updating cube indexes
				this.cubes.forEach(function (c) { c.reorient(); });

				this.isActive = false; 

				app.checkIsSolved();
			}
		}
	}
};

function RubikRotateEvent(point) {
	this.x = point.x;
	this.y = point.y;
}

RubikRotateEvent.prototype = {
	handleMousemove: function (p) {
		var dx, dy;

		dx = p.x - this.x;
		dy = p.y - this.y;

		if (dx == 0 && dy == 0) { return true; }

		if (Math.abs(dx) > settings.cubeSize || 
			Math.abs(dy) > settings.cubeSize) { return false; }

		// screen coords are inverted.
		rubik.rotate(-dy * settings.rubikRotSpeed, -dx * settings.rubikRotSpeed, 0);

		this.x = p.x;
		this.y = p.y;

		return true;
	},

	handleMouseup: function (e) {
		this.dispose();
		return false;
	},

	dispose: function () { }
};

function CubeRotateEvent(point, face) {

	this.x    = point.x;
	this.y    = point.y;
	this.face = face;

	this.resetArrowAnimation = this.resetArrowAnimation.bind(this);
	
	this.initialize();
}

CubeRotateEvent.prototype = {
	initialize: function () {
		var center = planeHelper.findCenter(this.face.outerVertices);

		this.state  = CubeRotateEvent.states.waitingForInput;
		this.planes = [
			this.mapPlane(Face.planes.above, new Vertex( 90,   0, 0), '#ff00ff'), // purple
			this.mapPlane(Face.planes.below, new Vertex(-90,   0, 0), '#00ffff'), // green blue
			this.mapPlane(Face.planes.left,  new Vertex(  0,  90, 0), '#ffff00'), // yellow?
			this.mapPlane(Face.planes.right, new Vertex(  0, -90, 0), '#ffffff'), // white
		];

		this.arrows = [
			new Arrow(center, new Vertex(0,0,  0, Vertex.units.degrees), this.face.rotation, this.face.parent, false),
			new Arrow(center, new Vertex(0,0, 90, Vertex.units.degrees), this.face.rotation, this.face.parent, false),
			new Arrow(center, new Vertex(0,0,180, Vertex.units.degrees), this.face.rotation, this.face.parent, false),
			new Arrow(center, new Vertex(0,0,270, Vertex.units.degrees), this.face.rotation, this.face.parent, false)
		];

		this.arrowRenderTimeout   = 500;
		this.arrowRenderTimeoutId = setTimeout(this.resetArrowAnimation, this.arrowRenderTimeout);

		app.addChildren(this.arrows);

		this.face.active = true;
	},

	mapPlane: function (planeVertices, rotVertex, color) {

		var faceRotation = this.face.rotation;
		var cube         = this.face.parent;

		var vertices = this.face.outerVertices.map(function (v, i) {
			v = vertexHelper.add(
				v,
				vertexHelper.multiply(planeVertices[i], settings.cubeSize)
			);

			return vertexHelper.toGlobalCoord(v, faceRotation, cube);
		});

		rotVertex = vertexHelper.rotate(rotVertex, faceRotation);
		rotVertex = vertexHelper.rotate(rotVertex, cube.rotation);
		rotVertex = vertexHelper.round (rotVertex, 4);

		return new Plane(vertices, rotVertex, color);
	},

	resetArrowAnimation: function (e) {
		this.arrowRenderTimeoutId = null;
		this.arrows.forEach(function (a) {
			a.resetAnimation();
		});
	},

	hideArrows: function () {
		this.arrows.forEach(function (a) { a.hide(); });
	},

	checkForPlaneMatch: function (p) {
		var dx, dy, cubes, index, rotation, state;

		dx    = p.x - this.x;
		dy    = p.y - this.y;
		state = this.state; 

		if (dx == 0 && dy == 0) { return true; }

		if (Math.abs(dx) > settings.cubeSize || 
			Math.abs(dy) > settings.cubeSize) { return false; }

		for (var i = 0; i < this.planes.length; i++) {
			if (planeHelper.containsPoint(this.planes[i].points, p)) {

				rotation = this.planes[i].targetRotation;
				index    = this.face.parent.index;
				cubes    = rubik.cubes.filter(function (c) {
					return (!rotation.x || c.index.x == index.x) 
					    && (!rotation.y || c.index.y == index.y)
					    && (!rotation.z || c.index.z == index.z);
				});

				this.modifier = new CubeRotationModifier(cubes, rotation);
				this.state    = CubeRotateEvent.states.rotatingCubes;
				rubik.modifiers.push(this.modifier);
				break;
			}
		}

		this.arrows.forEach(function (a) { a.hide(); });
		if (this.arrowRenderTimeoutId) { clearTimeout(this.arrowRenderTimeoutId); }

		this.arrowRenderTimeoutId = state == this.state
			? setTimeout(this.resetArrowAnimation, this.arrowRenderTimeout)
			: null;

		return true;
	},

	checkModifierStillActive: function (p) {
		if (!this.modifier || 
			!this.modifier.isActive) {

			 this.dispose();
		}

		return true;
	},

	handleMousemove: function (p) {

		var stillActive;

		switch (this.state) {

			case CubeRotateEvent.states.waitingForInput: stillActive = this.checkForPlaneMatch(p);       break;
			case CubeRotateEvent.states.rotatingCubes:   stillActive = this.checkModifierStillActive(p); break;
		}

		this.x = p.x;
		this.y = p.y;

		return stillActive;
	},

	handleMouseup: function () {
		this.dispose();
		return false;
	},

	dispose: function () {
		if (this.arrowRenderTimeoutId) { clearTimeout(this.arrowRenderTimeoutId); }
		app.removeChildren(this.arrows.map(function (a) { return a.id; }));
		this.face.active = false;
		this.modifier    = null;
	}
};

CubeRotateEvent.states = {
	waitingForInput: 0,
	rotatingCubes:   1
};

function Controls(container) {

	this.container = container || window;

	this.onResize     = this.onResize    .bind(this);
	this.onKeypress   = this.onKeypress  .bind(this);
	this.onMousedown  = this.onMousedown .bind(this);
	this.onMousemove  = this.onMousemove .bind(this);
	this.onMouseup    = this.onMouseup   .bind(this);
	this.onTouchstart = this.onTouchstart.bind(this);
	this.onTouchmove  = this.onTouchmove .bind(this);
	this.onTouchend   = this.onTouchend  .bind(this);

	this.initialize();
}

Controls.prototype = {
	initialize: function () {
		window.addEventListener('resize', this.onResize);

		// keyboard events
		window.addEventListener('keypress', this.onKeypress);

		// mouse events
		this.container.addEventListener('mousedown', this.onMousedown);
		this.container.addEventListener('mouseup',   this.onMouseup);

		// touch events
		this.container.addEventListener('touchstart', this.onTouchstart);
		this.container.addEventListener('touchend',   this.onTouchend);

	},

	dispose: function () {
		window.removeEventListener('resize', this.onResize);

		// keyboard events
		window.removeEventListener('keypress', this.onKeypress);

		// mouse events
		this.container.removeEventListener('movedown',  this.onMousedown);
		this.container.removeEventListener('mousemove', this.onMousemove);
		this.container.removeEventListener('moveup',    this.onMouseup);

		// touch events
		this.container.removeEventListener('touchstart', this.onTouchstart);
		this.container.removeEventListener('touchmove',  this.onTouchmove);
		this.container.removeEventListener('touchend',   this.onTouchend);
	},

	onResize: function (e) {

		winWidth  = window.innerWidth;
		winHeight = window.innerHeight;

		settings.rubikRotSpeed = settings.rubikRotPerWin / Math.min(winWidth, winHeight);
		settings.screenCenter  = new Point(winWidth / 2, winHeight / 2);
	},

	onKeypress: function (e) { 
		
		switch (e.which) {
			case Controls.keys.r: app.initialize(true);  break;
			case Controls.keys.q: app.isRunning = false; break;
			case Controls.keys.w: if (!app.isRunning) { app.isRunning = true; app.loop(); } break;
		}
	},

	handleMousedown: function (type, p) {
		if (settings.allowInput) {
			var match = rubik.faces.find(function (face) {
				return face.isOutsideFacing 
				    && face.isClockwise
				    && planeHelper.containsPoint(face.outerPoints, p);
			});

			// if the mouse click is within the rubik cube, then
			// the user is rotating a specific column or row.
			if (match) { this.mouseEvent = new CubeRotateEvent(p, match); }

			// otherwise, the user is rotating the entire cube.
			else { this.mouseEvent = new RubikRotateEvent(p); }

			this.container.addEventListener('mousemove', this.onMousemove);
			this.container.addEventListener('touchmove', this.onTouchmove);
		}
	},

	handleMousemove: function (type, p) {
		if (settings.allowInput &&
			this.mouseEvent && 
			this.mouseEvent.handleMousemove &&
			this.mouseEvent.handleMousemove(p) == false) { 

			this.handleMouseup(p);
		}
	},

	handleMouseup: function (type) {
		if (!this.mouseEvent || this.mouseEvent.handleMouseup() == false) {
			this.mouseEvent = null;
			this.touchEvent = null;

			window.removeEventListener('mousemove', this.onMousemove);
			window.removeEventListener('touchmove', this.onTouchmove);
		}
	},

	onMousedown:  function (e) { this.handleMousedown(Controls.eventTypes.mouse, new Point(e.clientX, e.clientY)); },
	onMousemove:  function (e) { this.handleMousemove(Controls.eventTypes.mouse, new Point(e.clientX, e.clientY)); },
	onMouseup:    function (e) { this.handleMouseup  (Controls.eventTypes.mouse); },
	onTouchstart: function (e) { this.handleMousedown(Controls.eventTypes.touch, new Point(e.touches[0].clientX, e.touches[0].clientY)); },
	onTouchmove:  function (e) { this.handleMousemove(Controls.eventTypes.touch, new Point(e.touches[0].clientX, e.touches[0].clientY)); },
	onTouchend:   function (e) { this.handleMouseup  (Controls.eventTypes.touch); }
};

Controls.eventTypes = {
	mouse: 1,
	touch: 2,
	key:   3
};

Controls.keys = {
	A: 65,
	D: 68,
	E: 69,
	F: 70,
	Q: 81,
	R: 82,
	S: 83,
	T: 84,
	W: 87,
	q: 113,
	r: 114,
	w: 119
};

function Camera(viewingAngle, nearClippingPlane, farClippingPlane) {

	this.position = new Vertex();
	this.target   = new Vertex();
	this.normal   = new Vertex(0, 0, 1);
	this.distance = farClippingPlane;
}

Camera.prototype = {
	moveTo: function (x,y,z) { this.position = new Vertex(x,y,z); },
	lookAt: function (x,y,z) { this.target   = new Vertex(x,y,z); }
};

function MenuItem(tag, props) {
	
	this.tag      = tag;
	this.min      = props.min;
	this.max      = props.max;
	this.value    = props.value;
	this.onChange = props.onChange;
	this.els      = {};

	this.onMousedown  = this.onMousedown .bind(this);
	this.onMousemove  = this.onMousemove .bind(this);
	this.onMouseout   = this.onMouseout  .bind(this);
	this.onMouseup    = this.onMouseup   .bind(this);
	this.onTouchstart = this.onTouchstart.bind(this);
	this.onTouchmove  = this.onTouchmove .bind(this);
	this.onTouchend   = this.onTouchend  .bind(this);

	this.initialize();
}

MenuItem.prototype = {
	initialize: function () {
		this.els = {
			value: document.querySelector(this.tag + Menu.tags.input),
			bar:   document.querySelector(this.tag + Menu.tags.bar),
			fill:  document.querySelector(this.tag + Menu.tags.bar + Menu.tags.fill)
		};

		this.setValue(this.value);

		this.els.bar.addEventListener('mousedown',  this.onMousedown);
		this.els.bar.addEventListener('mouseout',   this.onMouseout);
		this.els.bar.addEventListener('mouseup',    this.onMouseup);
		this.els.bar.addEventListener('touchstart', this.onTouchstart);
		this.els.bar.addEventListener('touchend',   this.onTouchend);
	},

	dispose: function () {
		this.els.bar.removeEventListener('mousedown',  this.onMousedown);
		this.els.bar.removeEventListener('mousemove',  this.onMousemove);
		this.els.bar.removeEventListener('mouseup',    this.onMouseup);
		this.els.bar.removeEventListener('touchstart', this.onTouchstart);
		this.els.bar.removeEventListener('touchmove',  this.onTouchmove);
		this.els.bar.removeEventListener('touchend',   this.onTouchend);
	},

	setValue: function (value, percent) {
		if (this.els.value && 
			this.els.value.innerText != value) {
			this.els.value.innerText = value;
		}

		if (this.els.fill) {
			if (!percent) { percent = (value - this.min) / this.max; }

			this.els.fill.style.width = (100 * percent).toFixed(2) + '%';
		}

		this.value = value;
	},

	getValue: function () { return this.value; },

	onMousedown:  function (e) { e.stopPropagation(); this.handleMousedown(Controls.eventTypes.mouse, new Point(e.clientX, e.clientY)); },
	onMousemove:  function (e) { e.stopPropagation(); this.handleMousemove(Controls.eventTypes.mouse, new Point(e.clientX, e.clientY)); },
	onMouseup:    function (e) { e.stopPropagation(); this.handleMouseup  (Controls.eventTypes.mouse); },
	onTouchstart: function (e) { e.stopPropagation(); this.handleMousedown(Controls.eventTypes.touch, new Point(e.touches[0].clientX, e.touches[0].clientY)); },
	onTouchmove:  function (e) { e.stopPropagation(); this.handleMousemove(Controls.eventTypes.touch, new Point(e.touches[0].clientX, e.touches[0].clientY)); },
	onTouchend:   function (e) { e.stopPropagation(); this.handleMouseup  (Controls.eventTypes.touch); },
	onMouseout:   function (e) { e.stopPropagation(); this.handleMousemove(Controls.eventTypes.mouse, new Point(e.clientX, e.clientY)); },

	handleMousedown: function (type, p) {
		if (settings.allowInput) { 

			this.isMousedown = true;

			this.handleMousemove(type, p);

			switch (type) {
				case Controls.eventTypes.mouse: this.els.bar.addEventListener('mousemove', this.onMousemove); break;
				case Controls.eventTypes.touch: this.els.bar.addEventListener('touchmove', this.onTouchmove); break;
			}
		}
	},

	handleMousemove: function (type, p) {
		if (settings.allowInput && this.isMousedown && this.els.bar) {

			var bounds  = this.els.bar.getBoundingClientRect();
			var left    = p.x - bounds.left;
			var top     = p.y - bounds.top;

			if (left < 0 || 
				top  < 0 || 
				left + bounds.left > bounds.right || 
				top  + bounds.top  > bounds.bottom) { 

				return this.handleMouseup(type);
			}

			var width   = this.els.bar.clientWidth;
			var percent = Math.min(left / width, 1);
			var value   = Math.min(Math.round(percent * (this.max - this.min)) + this.min, this.max);

			this.setValue(value, percent);

			if (typeof this.onChange == 'function') {
				this.onChange(value);
			}
		}
	},

	handleMouseup: function (type) {
		this.isMousedown = false;

		this.els.bar.removeEventListener('mousemove', this.onMousemove);
		this.els.bar.removeEventListener('touchmove', this.onTouchmove);
	}
};

function Menu() {

	this.isOpen = true;

	this.toggleMenu          = this.toggleMenu         .bind(this);
	this.solveRubik          = this.solveRubik         .bind(this);
	this.newGame             = this.newGame            .bind(this);
	this.changeRotationTicks = this.changeRotationTicks.bind(this);

	this.initialize();
}

Menu.prototype = {
	initialize: function () {

		var dimensionsProps, rotationTicksProps, randomizeFactorProps, newGame;

		     dimensionsProps = { value:  3, max:   7, min: 1 };
		  rotationTicksProps = { value: 20, max:  40, min: 5, onChange: this.changeRotationTicks };
		randomizeFactorProps = { value: 40, max: 100, min: 0 };

		newGame = this.newGame;

		this.els = {
			container:   document.querySelector   (Menu.tags.container),
			header:      document.querySelector   (Menu.tags.header),
			solveBtn:    document.querySelector   (Menu.tags.solveBtn),
			newGameBtns: document.querySelectorAll(Menu.tags.newGameBtn),
			winPanel:    document.querySelector   (Menu.tags.winPanel)
		};

		if (this.els.container.classList.contains(Menu.tags.expandedClass) != this.isOpen) {
			if (this.isOpen) { this.els.container.classList.add   (tag); }
			else             { this.els.container.classList.remove(tag); }
		}

		this.randomizeFactor = new MenuItem(Menu.tags.randomizeFactor, randomizeFactorProps);
		this.rotationTicks   = new MenuItem(Menu.tags.rotationTicks,     rotationTicksProps);
		this.dimensions      = new MenuItem(Menu.tags.dimensions,           dimensionsProps);

		this.els.header  .addEventListener('click', this.toggleMenu);
		this.els.solveBtn.addEventListener('click', this.solveRubik);

		this.els.newGameBtns.forEach(function (el) { el.addEventListener('click', newGame); });
	},

	changeRotationTicks: function (value) {
		settings.rotationTicks = value;
	},

	showWinPanel: function () {
		var panel = this.els.winPanel;

		panel.classList.add(Menu.tags.displayClass);

		if (this.displayTimeoutId) { window.clearTimeout(this.displayTimeoutId); }

		this.displayTimeoutId = window.setTimeout(function () {
			panel.classList.remove(Menu.tags.displayClass);
		}, 4000);
		cashSet(1, "Rubik");
	},

	toggleMenu: function () {
		var tag = Menu.tags.expandedClass;

		this.isOpen = !this.isOpen;

		if (this.isOpen) { this.els.container.classList.add   (tag); }
		else             { this.els.container.classList.remove(tag); }
	},

	newGame: function () {
		settings.cubesPerSide    = this.dimensions     .getValue();
		settings.randomizeFactor = this.randomizeFactor.getValue();

		app.newGame();
	},

	solveRubik: function () {
		rubik.initialize();
	},

	dispose: function () {
		var newGame = this.newGame;

		this.els.header  .removeEventListener('click', this.toggleMenu);
		this.els.solveBtn.removeEventListener('click', this.solveRubik);

		this.els.newGameBtns.forEach(function (el) { el.removeEventListener('click', newGame); });
	}
};

Menu.tags = {

	// element queries
	container:       '.menu',
	header:          '.menu-header',
	bar:             ' .fill-bar',
	fill: 			 ' .fill',
	input: 			 ' .input',
	dimensions:      '*[data-target="dimensions"]',
	randomizeFactor: '*[data-target="randomize-factor"]',
	rotationTicks:   '*[data-target="rotation-ticks"]',
	solveBtn: 	     '.btn[data-target="solve"]',
	newGameBtn:		 '.btn[data-target="newgame"]',
	winPanel: 		 '.cover',

	// class tags
	expandedClass: 'expanded',
	displayClass:  'display'
};

function App() {
	var parent = document.querySelector('.app') || document.body;

	this.element = document.createElement('canvas');
	this.element.id = 'scene';
	this.element.style.width  =
	this.element.style.height = '100%';
	parent.append(this.element);

	this.onResize = this.onResize.bind(this);
	this.resize   = this.resize  .bind(this);
	this.loop     = this.loop    .bind(this);

	window.addEventListener('resize', this.onResize);

	this.initialize();
	this.randomize();
	this.loop();
}

App.prototype = {
	initialize: function () {
		var min;

		this.isRunning = true;

		settings.screen = {
			width:  window.innerWidth,
			height: window.innerHeight,
			center: new Point(window.innerWidth / 2, window.innerHeight / 2)
		};

		min = Math.min(settings.screen.width, settings.screen.height);

		settings.cubeSize      = Math.min(min / (settings.cubesPerSide * 2), 100);
		settings.rubikRotSpeed = settings.rubikRotPerWin / Math.min(settings.screen.width, settings.screen.height);

		camera = new Camera(45, 0.1, settings.cubesPerSide * settings.cubeSize * 10);
		camera.moveTo(0,0,-(settings.cubesPerSide + 1) * settings.cubeSize);

		controls = new Controls();
		rubik    = new Rubik();

		menu = new Menu();

		this.element.width  = settings.screen.width;
		this.element.height = settings.screen.height;

		this.scene    = this.element.getContext('2d');
		this.children = [rubik];
	},

	dispose: function () {
		window.removeEventListener('resize', this.onResize);

		if (this.element) {
			this.element.remove();
			delete this.element;
		}
	},

	onResize: function () {
		if (this.resizeTimeoutId) { clearTimeout(this.resizeTimeoutId); }
		this.resizeTimeoutId = setTimeout(this.resize, 200);
	},

	resize: function () {
		var min, i;

		this.resizeTimeoutId = null;

		settings.screen = {
			width:  window.innerWidth,
			height: window.innerHeight,
			center: new Point(window.innerWidth / 2, window.innerHeight / 2)
		};

		min = Math.min(settings.screen.width, settings.screen.height);

		settings.cubeSize      = Math.min(min / (settings.cubesPerSide * 2), 100);
		settings.rubikRotSpeed = settings.rubikRotPerWin / Math.min(settings.screen.width, settings.screen.height);

		this.element.width  = settings.screen.width;
		this.element.height = settings.screen.height;

		for (i = 0; i < this.children.length; i++) {
			if (typeof this.children[i].onResize == 'function') {
				this.children[i].onResize();
			}
		}
	},

	checkIsSolved: function () {

		if (rubik.isSolved()) { 
			menu.showWinPanel();
		}
	},

	loop: function () {
		if (this.isRunning) {
			this.update();
			this.render();

			requestAnimationFrame(this.loop);
		}
	},

	newGame: function () {
		var min = Math.min(settings.screen.width, settings.screen.height);

		settings.cubeSize      = Math.min(min / (settings.cubesPerSide * 2), 100);
		settings.rubikRotSpeed = settings.rubikRotPerWin / Math.min(settings.screen.width, settings.screen.height);

		rubik.initialize();
		 this.randomize();
	},

	randomize: function () {
		var rand, i, cubes, index, rotation, ids;

		for (i = 0; i < settings.randomizeFactor; i++) {
			rand  = Math.floor(Math.random() * rubik.cubes.length);
			index = rubik.cubes[rand].index;
			rand  = Math.floor(Math.random() * 6);

			switch (rand) {
				case 0: rotation = new Vertex( 90,  0,  0, Vertex.units.degrees); break;
				case 1: rotation = new Vertex(-90,  0,  0, Vertex.units.degrees); break;
				case 2: rotation = new Vertex(  0, 90,  0, Vertex.units.degrees); break;
				case 3: rotation = new Vertex(  0,-90,  0, Vertex.units.degrees); break;
				case 4: rotation = new Vertex(  0,  0, 90, Vertex.units.degrees); break;
				case 5: rotation = new Vertex(  0,  0,-90, Vertex.units.degrees); break;
			}

			cubes = rubik.cubes.filter(function (c) {
				return (!rotation.x || c.index.x == index.x) 
				    && (!rotation.y || c.index.y == index.y)
				    && (!rotation.z || c.index.z == index.z);
			});

			ids = cubes.map(function (c) { return c.id; });

			cubes.forEach(function (c) { c.rotate(rotation.x, rotation.y, rotation.z); });

			rubik.faces.forEach(function (face) { 
				if (ids.includes(face.parent.id)) {
					Face.reorient(face);
				}
			});

			cubes.forEach(function (c) { c.reorient(); });
		}
	},

	addChildren: function (children) {
		if (!children) { return; }

		if (typeof children == 'object') {
			if (!children instanceof Array) { return this.children.push(children); }

			for (var i = 0; i < children.length; i++) {
				this.children.push(children[i]);
			}
		}
	},

	removeChildren: function () {
		if (!arguments.length) { return; }

		var ids = arguments.length == 1 && arguments[0] instanceof Array 
				? arguments[0]
				: [].slice.call(arguments);

		this.children = this.children.filter(function (c) { return !ids.includes(c.id); });
	},

	update: function () {
		for (var i = 0; i < this.children.length; i++) {
			this.children[i].update();
		}
	},

	render: function () {
		this.scene.clearRect(0, 0, settings.screen.width, settings.screen.height);
		
		for (var i = 0; i < this.children.length; i++) {
			this.children[i].render(this.scene);
		}
	}
};

(function (callback) {
	// browser event has already occurred.
	if (document.readyState === "complete") {
		return setTimeout(callback);
	}

	// Mozilla, Opera and webkit style
	if (window.addEventListener) {
		return window.addEventListener("load", callback, false);
	}

	// If IE event model is used
	if (window.attachEvent) {
		return window.attachEvent("onload", callback);
	}

}(function () { app = new App(); }));